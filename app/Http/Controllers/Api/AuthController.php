<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseApiController
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return $this->sendError('Unauthorized', ['error' => 'Invalid credentials'], 401);
            }

            $token = $user->createToken('mobile-app')->plainTextToken;

            $data = [
                'user' => $user,
                'token' => $token,
            ];

            return $this->sendResponse($data, 'User logged in successfully.');

        } catch (ValidationException $e) {
            return $this->sendError('Validation Error', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->sendError('Login failed', ['error' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->sendResponse([], 'User logged out successfully.');
    }

    public function user(Request $request)
    {
        return $this->sendResponse($request->user(), 'User profile retrieved successfully.');
    }
}
