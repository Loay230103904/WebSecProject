<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\BoughtProduct;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    public function list()
    {
        $products = Product::select('id', 'code', 'name', 'model', 'description', 'price', 'stock', 'photo')->get();
        return response()->json(['products' => $products]);
    }



    public function purchase(Request $request, $id)
    {
            $product = Product::findOrFail($id);
            $user = Auth::user();
        
            // Check if user has enough credit
            if ($user->account_credit < $product->price) {
                return response()->json(['erorr' => 'no credit']);
            }
        

        
            // Reduce stock
            if ($product->stock > 0) {
                $product->stock -= 1;
                $product->save();
            } else {
                return response()->json(['erorr' => 'out of stock']);
            }

            // minus price from user credit
            $user->account_credit -= $product->price;
            $user->save();

            BoughtProduct::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);

        return response()->json(['message' => 'Purchase successful']);
    }

    public function boughtProducts()
    {
        $bought = BoughtProduct::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return response()->json(['bought_products' => $bought]);
    }
}
