<?php

namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Artisan;

use App\Http\Controllers\Controller;
use App\Models\User;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;
use App\Mail\TemporaryPasswordEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;


class UsersController extends Controller {
    use ValidatesRequests;

    
    
    public function index(Request $request) {
        if (!auth()->check()) {
            abort(401, 'User not authenticated'); // Ensure user is logged in
        }
    
        $user = auth()->user();
    
        
        if ($user->hasRole('admin')) {
            // Admins can see all users
            $query = User::query();
        } elseif ($user->hasRole('employee')) {
            // Employees can only see customers
            $query = User::whereHas('roles', function ($q) {
                $q->where('name', 'customer');
            });
        } else {
            // Customers and unauthorized users should see nothing (or redirect)
            abort(403, 'Unauthorized access');
        }
        
    
        // Apply search filter if input exists
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                ->orWhere('email', 'LIKE', "%{$request->search}%")
                ->orWhereHas('roles', function ($q) use ($request) {
                    $q->where('name', 'LIKE', "%{$request->search}%");
                });
            });
        }
    
        $users = $query->paginate(10);
    
        return view('users.index', compact('users'));
    }





    public function register(Request $request) {
        return view('users.register');
    }

       public function doRegister(Request $request) {

    	try {
    		$this->validate($request, [
	        'name' => ['required', 'string', 'min:5'],
	        'email' => ['required', 'email', 'unique:users'],
	        'password' => ['required', 'confirmed', Password::min(8)],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'], 
	    	]);
    	}
    	catch(\Exception $e) {
    		return redirect()->back()->withInput($request->input())->withErrors('Invalid registration information.');
    	}

    	
    	$user =  new User();
	    $user->name = $request->name;
	    $user->email = $request->email;
	    $user->password = bcrypt($request->password); //Secure
        $user->address = $request->address;
        $user->phone = $request->phone;
        $user->assignRole('customer');

	    $user->save();

        // Send verification email
        try {
            $token = Crypt::encryptString(json_encode([
                'id' => $user->id,
                'email' => $user->email,
            ]));

            $link = route('verify', ['token' => $token]);
            
            \Log::info('Sending verification email to: ' . $user->email);
            \Log::info('Verification link: ' . $link);
            
            Mail::to($user->email)->send(new VerificationEmail($link, $user->name));
            \Log::info('Verification email sent successfully');
        } catch (\Exception $e) {
            \Log::error('Failed to send verification email: ' . $e->getMessage());
            return redirect()->back()->withInput($request->input())->withErrors('Failed to send verification email. Please try again.');
        }

        return redirect()->route('login')->with('message', 'Please check your email to verify your account.');
    }

    
    
    public function login(Request $request) {
        return view('users.login');
        }

        
    public function doLogin(Request $request) {

        if(!Auth::attempt(['email' => $request->email, 'password' => $request->password]))
            return redirect()->back()->withInput($request->input())->withErrors('Invalid login information.');

        $user = User::where('email', $request->email)->first();

        // ❗️Check if email is verified
        if (!$user->email_verified_at) {
        Auth::logout(); // تأمين زيادة علشان ميكملش السيشن
        return redirect()->back()->withInput($request->input())->withErrors('Your email is not verified Check Your inbox.');
    }

        Auth::setUser($user);
         if (Hash::check($request->password, $user->password)) {
            if (Str::length($request->password) === 5) {
                // Assuming temp passwords are always 5 chars
                session(['force_password_change' => true]);
                return redirect()->route('edit_password');
            }}
        return redirect("/");
        }


         public function verify(Request $request)
{
    $decryptedData = json_decode(Crypt::decryptString($request->token), true);
    $user = User::find($decryptedData['id']);

    if (!$user) abort(401);

    $user->email_verified_at = Carbon::now();
    $user->save();

    return view('users.verified', compact('user'));
}

    public function doLogout(Request $request) {

        Auth::logout();
        return redirect("/");
        }


        
    public function forgotPasswordForm()
{
    return view('users.forgot_password');
}




public function sendTemporaryPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()->withErrors('Email not found.');
    }

    // Generate temp password
    $tempPassword = Str::random(5);
    $user->password = bcrypt($tempPassword);
    $user->save();


    Mail::to($user->email)->send(new TemporaryPasswordEmail($user->name, $tempPassword));


    return redirect()->route('login')->with('message', 'Temporary password sent to your email.');


}


 public function editPassword(Request $request, User $user = null) {

        $user = $user??auth()->user();
        if(auth()->id()!=$user?->id) {
            if(!auth()->user()->hasPermissionTo('edit_users')) abort(401);
        }

        return view('users.edit_password', compact('user'));
    }

    public function savePassword(Request $request, User $user) {

        if(auth()->id()==$user?->id) {
            
            $this->validate($request, [
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);

            if(!Auth::attempt(['email' => $user->email, 'password' => $request->old_password])) {
                
                Auth::logout();
                return redirect('/');
            }
        }
        else if(!auth()->user()->hasPermissionTo('edit_users')) {

            abort(401);
        }

        $user->password = bcrypt($request->password); //Secure
        $user->save();

        return redirect(route('profile', ['user'=>$user->id]));
    }

// _______________________________________________________________________________
        public function profile(Request $request, User $user = null) {
            $user = $user??auth()->user();
            if(auth()->id()!=$user?->id) {
                if(!auth()->user()->hasPermissionTo('show_users')) abort(401);} 


                $permissions = [];
                foreach($user->permissions as $permission) {
                    $permissions[] = $permission;
                }
                foreach($user->roles as $role) {
                    foreach($role->permissions as $permission) {
                        $permissions[] = $permission;
                    }
                }
                
                return view('users.profile', compact('user', 'permissions'));
        }

        public function edit(Request $request, User $user = null) {

            $user = $user??auth()->user();
            if(auth()->id()!=$user?->id) {
                if(!auth()->user()->hasPermissionTo('edit_users')) abort(401);
            }
            
            
            $roles = [];
            foreach(Role::all() as $role) {
                $role->taken = ($user->hasRole($role->name));
                $roles[] = $role;
            }
            
            
            $permissions = [];
            $directPermissionsIds = $user->permissions()->pluck('id')->toArray();
            foreach(Permission::all() as $permission) {
                $permission->taken = in_array($permission->id, $directPermissionsIds);
                $permissions[] = $permission;
            }
            return view('users.edit', compact('user', 'roles', 'permissions'));
        
        }

        public function save(Request $request, User $user) {
            // Authorization: Allow user to update their own profile or require permission
            if (auth()->id() != $user->id && !auth()->user()->hasPermissionTo('edit_users')) {
                abort(403, 'Unauthorized action.');
            }
        
            $this->validate($request, [
                'name' => ['required', 'string', 'min:4'],

            ]);

        

            $user->name = $request->name;
        
            // Password update with old password verification and validation
            if ($request->filled('password')) {
                $this->validate($request, [
                    'old_password' => ['required'],
                    'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
                ]);

                if (!Hash::check($request->old_password, $user->password)) {
                    return back()->withErrors(['old_password' => 'Old password is incorrect']);
                }

                $user->password = bcrypt($request->password);
            }

            if (auth()->user()->hasPermissionTo('add_credit')) {
                $this->validate($request, [
                    'account_credit' => ['required', 'numeric', 'min:0'],
                ]);
                $user->account_credit += $request->account_credit;
            }
            
            
            if(auth()->user()->hasPermissionTo('edit_users')) {
                // Only sync roles if they exist in the request
                if ($request->has('roles')) {
                    $user->syncRoles($request->roles);
                }
            
                // Only sync permissions if they exist in the request
                if ($request->has('permissions')) {
                    $user->syncPermissions($request->permissions);
                }
            
                Artisan::call('cache:clear');
            }
            

            

            $user->save();


            
            return redirect(route('profile', ['user' => $user->id]));
        }

        
        public function reset(User $user){
            $user->account_credit = 0;
            $user->save();
        
            return redirect()->route('users_index');
        }
        
        
        
}
    

