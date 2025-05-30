<?php
    namespace App\Http\Controllers\Web;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use DB;
    use App\Models\Product;
    use Illuminate\Foundation\Validation\ValidatesRequests;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Facades\Auth;
    use App\Models\BoughtProduct;






    class ProductsController extends Controller{
        use ValidatesRequests;
        
        public function __construct(){
            $this->middleware("auth:web")->except("list");
        }
    
        public function list(Request $request) {
            $query = Product::select("products.*");

            // Filter by keywords (search in name)
            $query->when($request->keywords, fn($q) => 
                $q->where("name", "like", "%{$request->keywords}%")
            );
            
            // Filter by min price
            $query->when($request->min_price, fn($q) => 
                $q->where("price", ">=", $request->min_price)
            );
            
            // Filter by max price
            $query->when($request->max_price, fn($q) => 
                $q->where("price", "<=", $request->max_price)
            );
            
            // Sorting (order by column)
            $query->when($request->order_by, fn($q) => 
                $q->orderBy($request->order_by, $request->order_direction ?? "ASC")
            );
            
            // Get the filtered results
            $products = $query->get();
            
            


            return view("products.list", compact('products'));
        }

        public function edit(Request $request, Product $product = null) {
            
            if (!  auth()->user()->hasPermissionTo('edit_products')) {
                abort(401);
            }

            $product = $product??new Product();
            return view("products.edit", compact('product'));
        }


        public function save(Request $request, Product $product = null) {
            $this->validate($request, [
                'code' => ['required', 'string', 'max:32'],
                'name' => ['required', 'string', 'max:128'],
                'model' => ['required', 'string', 'max:256'],
                'description' => ['required', 'string', 'max:1024'],
                'price' => ['required', 'numeric', 'min:0'],
            ]);



            $product = $product??new Product();
            $product->fill($request->all());
            $product->save();


            return redirect()->route('products_list');
        }


        public function delete(Request $request, Product $product) {

            if (!auth()->user()->hasPermissionTo('delete_products')) {
                abort(401);
            }

            $product->delete();
            return redirect()->route('products_list');
        }




        public function purchase($id) {
            $product = Product::findOrFail($id);
            $user = Auth::user();
        
            // Check if user has enough credit
            if ($user->account_credit < $product->price) {
                return redirect()->back()->with('error', 'Insufficient credit.');
            }
        

        
            // Reduce stock
            if ($product->stock > 0) {
                $product->stock -= 1;
                $product->save();
            } else {
                return redirect()->back()->with('error', 'Out of stock.');
            }

            // minus price from user credit
            $user->account_credit -= $product->price;
            $user->save();

            BoughtProduct::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            
        
            return redirect()->back()->with('success', 'Purchase successful!');
        }



        public function boughtProducts() {
            $user = auth()->user();
            
            // If the user is an admin, employee, or delivery role, show all bought products (with optional filtering by state)
            if ($user->hasRole('admin') || $user->hasRole('employee') || $user->hasRole('delivery')) {
                $query = BoughtProduct::with('product', 'user');

                if (request()->has('state')) {
                    $query->where('state', request('state'));  // Filter by 'state' if passed in the URL
                }

                $boughtProducts = $query->orderBy('created_at', 'desc')->get();
            } else {
                // For normal customers, show only their own bought products without filtering
                $boughtProducts = BoughtProduct::where('user_id', $user->id)->with('product')->orderBy('created_at', 'desc')->get();
            }

        
            return view('products.bought', compact('boughtProducts'));
        }

        public function markDelivered($id) {
            if (!auth()->user()->hasPermissionTo('delivery_operations')) {
                abort(401);
            }
            $boughtProduct = BoughtProduct::findOrFail($id);
            $boughtProduct->state = 'delivered';
            $boughtProduct->save();

            return redirect()->back()->with('success', 'Product marked as delivered.');
        }

        public function markRefused($id) {
            if (!auth()->user()->hasPermissionTo('delivery_operations')) {
                abort(401);
            }
            $boughtProduct = BoughtProduct::findOrFail($id);
            $boughtProduct->state = 'refused';
            $boughtProduct->save();

            return redirect()->back()->with('success', 'Product marked as refused.');
        }



        public function stockOperations() {

            if (!auth()->user()->hasPermissionTo('stock_operations')) {
                abort(401);
            }

            $lowStockProducts = Product::where('stock', '<=', 5)->get();
            return view('products.stock_operations', compact('lowStockProducts'));


        }

        public function increaseStock(Request $request, $id) {

            if (!auth()->user()->hasPermissionTo('stock_operations')) {
                abort(401);
            }
            $request->validate([
                'stock' => 'required|integer|min:1',
            ]);
            $product = Product::findOrFail($id);
            $product->stock += $request->stock;
            $product->save();

            return redirect()->route('stock_operations')->with('success', 'Stock increased successfully.');

        }




}

