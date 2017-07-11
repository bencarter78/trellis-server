<?php

namespace Tests\Feature\Api;

use App\Team;
use App\User;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProjectControllerTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();
        JWTAuth::shouldReceive('parseToken->authenticate')
            ->andReturn(factory(User::class)->create());
    }

    /** @test */
    public function it_creates_a_new_project_for_a_team()
    {
        $team = factory(Team::class)->create();

        $response = $this->post("/api/teams/{$team->id}/projects", [
            'name' => 'Demo Project',
            'description' => 'This is the description'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'ok' => true,
                         'project' => ['name' => 'Demo Project']
                     ]
                ]);
    }
}
