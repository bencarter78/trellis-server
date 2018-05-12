<?php

namespace Tests;

use App\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp()
    {
        parent::setUp();

        TestResponse::macro('data', function ($key) {
            if (is_array($this->original)) {
                return $this->original[$key];
            }

            return $this->original->getData()[$key];
        });

        EloquentCollection::macro('assertContains', function ($value) {
            Assert::assertTrue($this->contains($value), "Failed asserting that the collection contains the specified value.");
        });

        EloquentCollection::macro('assertNotContains', function ($value) {
            Assert::assertFalse($this->contains($value), "Failed asserting that the collection does not contain the specified value.");
        });

        EloquentCollection::macro('assertEquals', function ($items) {
            Assert::assertEquals(count($this), count($items));

            $this->zip($items)->each(function ($pair) {
                list($a, $b) = $pair;
                Assert::assertTrue($a->is($b), "$a is not $b");
            });
        });
    }

    /**
     * @return mixed
     */
    public function authUser()
    {
        $user = factory(User::class)->create();
        JWTAuth::shouldReceive('parseToken->authenticate')->andReturn($user);

        return $user;
    }

    /**
     * @param $class
     * @return \Mockery\MockInterface
     */
    public function mock($class)
    {
        return Mockery::mock($class);
    }
}
