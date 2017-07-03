<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * @var Client
     */
    private $client;

    /**
     * Create a new controller instance.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->middleware('guest')->except('logout');
        $this->client = $client;
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard('api')->user())
                ?: redirect()->intended($this->redirectPath());
    }

    /**
     * @param Request $request
     * @param         $user
     * @return \Illuminate\Http\JsonResponse
     */
    protected function authenticated(Request $request, $user)
    {
        return response()->json([
            'ok' => true,
            'data' => [
                'user' => $user,
                'csrf' => csrf_token(),
                'api_token' => Auth::user()->api_token,
            ]
        ]);
    }
}
