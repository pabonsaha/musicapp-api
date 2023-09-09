<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{


    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try
        {
            $user = User::find($id);

            return response()->json([
                'user' => $user,
            ],200);

        }
        catch(Exception $e)
        {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in user show',
            ],400);
        }
    }


    public function update(UserUpdateRequest $request, string $id)
    {
        try
        {
            $user = User::find($id);
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;

            $user->save();

            return response()->json([
                'user' => $user,
                'message' => 'User Details Updated',
            ],200);

        }
        catch(Exception $e)
        {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in user show',
            ],400);
        }
    }

}
