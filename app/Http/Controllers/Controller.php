<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param array $data
     * @param int   $status
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function response($data = [], $status = 200, $headers = [])
    {
        return response()->json([
            'data' => $data,
        ], $status, $headers);
    }

    /**
     * @param array $errors
     * @param int   $status
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseError($errors = [], $status = 500, $headers = [])
    {
        $errors = array_merge($errors, ['status' => $status]);

        return response()->json([
            'errors' => $errors,
        ], $status, $headers);
    }

    /**
     * Attempts to authenticate the user from the supplied JWT
     */
    public function userFromToken()
    {
        try {
            return JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return abort(401, 'Please log in to access ' . config('trellis.app.name'));
        }
    }
}
