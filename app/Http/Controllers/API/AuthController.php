<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends BaseController
{
    // User login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return $this->sendError('Unauthorised.', ['error' => 'Invalid credentials']);
            }

            // Get the authenticated user.
            $user = Auth::user();

            // (optional) Attach the role to the token.
            $token = JWTAuth::claims(['level' => $user->level])->fromUser($user);

            return $this->sendResponse($token, 'User login successfully.');
        } catch (JWTException $e) {
            return $this->sendError('Unauthorised.', ['error' => 'Could not create token']);
        }
    }

    // Get authenticated user
    public function getUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return $this->sendError('Unauthorised.', ['error' => 'User not found']);
            }
        } catch (JWTException $e) {
            return $this->sendError('Unauthorised.', ['error' => 'Invalid token']);
        }
        return $this->sendResponse($user, 'successfully.');
    }

    // User logout
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return $this->sendResponse([], 'Successfully logged out.');
    }
}
