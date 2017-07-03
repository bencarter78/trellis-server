<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        try {
            if (! $token = JWTAuth::attempt($request->only('email', 'password'))) {
                return $this->response([
                    'errors' => [
                        'status' => Response::HTTP_UNAUTHORIZED,
                        'title' => 'Invalid Credentials',
                        'details' => 'The credentials to not match.'
                    ],
                ], 401);
            }
        } catch (JWTException $e) {
            return $this->response([
                'errors' => [
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'title' => 'Token not created',
                    'details' => 'Could not create token.'
                ],
            ]);
        }

        return $this->response([
            'token' => $token,
            'user' => Auth::user(),
        ], Response::HTTP_OK);
    }
}
