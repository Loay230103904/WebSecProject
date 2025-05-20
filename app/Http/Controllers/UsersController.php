<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Mail\VerificationEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;




class UsersController extends Controller
{
    use ValidatesRequests;

    public function list(Request $request)
    {
            $authuser = Auth::user();
            if (!$authuser || !$authuser->can('show_users')) abort(403);

        $query = User::query();

        // Optional keyword search
        if ($request->keywords) {
            $query->where('name', 'like', '%' . $request->keywords . '%');
        }

        $users = $query->get();
        return view('users.list', compact('users'));
    }

    public function register()
    {
        return view('users.register');
    }

    public function doRegister(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:5'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'credit' => 0,  // Set default credit to 0
        ]);

        // ✅ Assign the customer role using Spatie
        $user->assignRole('customer');
        Auth::login($user);

        $title = "Verification Link";
        
        $token = Crypt::encryptString(json_encode(['id' => $user->id, 'email' => $user->email]));

        $link = route("verify", ['token' => $token]);

        Mail::to($user->email)->send(new VerificationEmail($link, $user->name));

        return redirect('/');
    }



    public function login()
    {
        return view('users.login');
    }

    public function doLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return redirect()->back()->withInput()->withErrors('Invalid login information.');
        }

        $user = User::where('email', $request->email)->first();
        if(!$user->email_verified_at)
            return redirect()->back()->withInput($request->input())
            ->withErrors('Your email is not verified.');


        return redirect('/');
    }

    public function doLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function verify(Request $request) {

        $decryptedData = json_decode(Crypt::decryptString($request->token), true);
        $user = User::find($decryptedData['id']);
        if(!$user) abort(401);
        $user->email_verified_at = Carbon::now();
        $user->save();
        return view('users.verified', compact('user'));
    }

    public function resendVerification(Request $request)
{
    $user = Auth::user();
    if ($user->email_verified_at) {
        return redirect('/')->with('message', 'Your email is already verified.');
    }

    $token = Crypt::encryptString(json_encode(['id' => $user->id, 'email' => $user->email]));
    $link = route("verify", ['token' => $token]);

    Mail::to($user->email)->send(new VerificationEmail($link, $user->name));

    return back()->with('success', 'Verification link resent.');
}



    public function profile(Request $request, User $user = null)
    {
        $user = $user ?? Auth::user();

        if (Auth::id() !== $user->id && !Auth::user()->can('show_users')) {
            abort(403);
        }

        $permissions = $user->getAllPermissions();
        return view('users.profile', compact('user', 'permissions'));
    }

    public function edit(Request $request, User $user = null)
{
    $user = $user ?? Auth::user();

    if (!Auth::check() || (Auth::id() !== $user->id && !Auth::user()->can('edit_users'))) {
        abort(403);
    }

    $roles = Role::all()->map(function ($role) use ($user) {
        $role->taken = $user->hasRole($role->name);
        return $role;
    });

    $permissions = Permission::all()->map(function ($permission) use ($user) {
        $permission->taken = $user->hasDirectPermission($permission);
        return $permission;
    });

    return view('users.edit', compact('user', 'roles', 'permissions'));
}


    public function save(Request $request, User $user)
    {
        if (Auth::id() !== $user->id && !Auth::user()->can('edit_users')) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'min:3'],
        ]);

        $user->name = $request->name;
        $user->save();

        if (Auth::user()->can('admin_users')) {
            $user->syncRoles($request->roles ?? []);
            $user->syncPermissions($request->permissions ?? []);
            Artisan::call('cache:clear');
        }

        return redirect()->route('profile', ['user' => $user->id]);
    }

    public function delete(Request $request, User $user)
    {
        if (!Auth::user()->can('delete_users')) {
            abort(403);
        }

        $user->delete();

        return redirect()->route('users');
    }

    public function editPassword(Request $request, User $user = null)
    {
        $user = $user ?? Auth::user();

        if (Auth::id() !== $user->id && !Auth::user()->can('edit_users')) {
            abort(403);
        }


        return view('users.edit_password', compact('user'));
    }

    public function savePassword(Request $request, User $user)
    {
        if (Auth::id() === $user->id) {
            $request->validate([
                'old_password' => ['required'],
                'password'     => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);

            if (!Hash::check($request->old_password, $user->password)) {
                return redirect()->back()->withErrors('Old password is incorrect.');
            }
        } else {
            if (!Auth::user()->can('edit_users')) {
                abort(403);
            }
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile', ['user' => $user->id]);
    }

    public function addCredit(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount'  => 'required|numeric|min:0.01',
        ]);

        $user = User::findOrFail($request->user_id);

        if (!$user->hasRole('customer')) {
            return back()->withErrors('You can only add credit to customers.');
        }

        $user->credit += $request->amount;
        $user->save();

        return back()->with('success', 'Credit added successfully.');
    }






}
