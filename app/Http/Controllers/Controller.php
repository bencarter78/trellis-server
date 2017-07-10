<?php

namespace App\Http\Controllers;

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
     * @param array $data
     * @param int   $status
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseError($data = [], $status = 500, $headers = [])
    {
        $data = array_merge($data, ['status' => $status]);

        return response()->json([
            'errors' => $data,
        ], $status, $headers);
    }
}
