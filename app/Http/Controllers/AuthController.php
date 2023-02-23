<?php


namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\EmailService;


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register','forgetPassword','changePassword']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => Role::where('name', 'user')->first()->id,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
    public function update(Request $request){
        $request->validate([
            'name' => 'string|max:255',
            'email' => 'string|email|max:255',
            'password' => 'string|min:6',
            
        ]);
        $user=$request->user();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role,
            'password' => Hash::make($request->password),
        ]);
        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }


    public function forgetPassword(Request $request)
        {
            $user = $request->user();
            if($request->isMethod('post')){
                $request->validate([
                    'email' => 'required|string|email',
                ]);
                $email = $request->email;
                $user  = User::where('email', $email)->first();
                if($user){
                    $full_name        = $user->name;
                    $activation_token = md5(uniqid()).$email.sha1($email);
                    $emailresetpwd    = new EmailService;
                    $subject          = "reset your password";
                    $emailresetpwd->resetPassword($subject,$email,$full_name,true,$activation_token);
                    $user = User::where('email', $email)->update(['rememberToken' => $activation_token ]);
                    return response()->json([
                                'status' => 'success',
                                'message' => 'We have send an email vereification to your email please verify that',
                                'name' => $full_name,
                                'token' => $activation_token,

                    ], 200);
                }else{
                    return response()->json([
                        'status' => 'error',
                        'message' => 'email doesn\'t exist',
            ], 404);
                }
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid method',
                ], 401);
            }

        }

        public function changePassword(Request $request)
        {
            $user= $request->user();

            if($request->isMethod('post')){
                $request->validate([
                    'password'         => 'required|min:8',
                    'confirm_password' => 'required|min:8|same:password',
                    'token'            => 'required'
                ]);
                $user = User::where('rememberToken', $request->token)->first();
                if($user){
                    $user->password = Hash::make($request->password);
                    $user->save();
                    return response()->json([
                       'statuts' => 'success',
                       'message' => 'your password has been updated successfuly',
                    ],200);
                }else{
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'you do not have permession to access into this page'
                    ],401);
                }
            }else{
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'method not allowd'
                    ],405);
            }
        }


}

