<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserLogoutRequest;
use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //

    public function register(UserRegistrationRequest $request)
    {
        try{
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'  => $request->email,
                'password'  =>Hash::make($request->password),
            ]);

           $token =  $user->createToken('user_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ]);

        }catch(\Exception $e)
        {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in Authentication',
            ]);
        }

    }

    public function login(UserLoginRequest $request)
    {
        try{

            $user = User::where('email',$request->email)->firstOrFail();
            if(Hash::check($request->input('password'),$user->password))
            {
                $token =  $user->createToken('user_token')->plainTextToken;

                return response()->json([
                    'user' => $user,
                    'token' => $token,
                ]);
            }

            return response()->json([
                'error' => 'Credential not matched',
            ],401);

        }
        catch(Exception $e)
        {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in login',
            ]);
        }
    }

    public function logout(UserLogoutRequest $request)
    {
        try{

            $user = User::firstOrFail($request->user_id);


            $user->tokens()->delete();

            return response()->json('user logged out',200);

        }
        catch(Exception $e)
        {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in logout',
            ]);
        }
    }


}
