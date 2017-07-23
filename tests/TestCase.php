<?php

namespace Tests;

use App\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @return mixed
     */
    public function authUser()
    {
        $user = factory(User::class)->create();
        JWTAuth::shouldReceive('parseToken->authenticate')->andReturn($user);

        return $user;
    }
}
