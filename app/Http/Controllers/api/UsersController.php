<?php
namespace App\Http\Controllers\Api;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Artisan;
use Illuminate\Validation\ValidationException;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Controllers\Api\UsersController;

    class UsersController extends Controller {

        public function login(Request $request) {
            if(!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return response()->json(['error' => 'Invalid login info.'], 401);
            }
            $user = User::where('email', $request->email)->select('id', 'name', 'email')->first();
            $token = $user->createToken('app');

            return response()->json(['token'=>$token->accessToken, 'user'=>$user->getAttributes()]);
        }


public function register(Request $request){
    $request->validate([
        'name' => ['required', 'string', 'min:5'],
        'email' => ['required', 'email', 'unique:users'],
        'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
        'address' => ['required', 'string', 'max:255'],
        'phone' => ['required', 'string', 'max:20'],
    ]);

    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = bcrypt($request->password);
    $user->address = $request->address;
    $user->phone = $request->phone;
    $user->assignRole('customer');
    $user->save();

    $token = $user->createToken('app');

    return response()->json(['token' => $token->accessToken, 'user' => $user]);
}


        public function profile(Request $request){
            $user = auth()->user();
            return response()->json(['user' => $user]);
        }
    

        public function logout(Request $request) {

            auth()->user()->token()->revoke();
        }
    }