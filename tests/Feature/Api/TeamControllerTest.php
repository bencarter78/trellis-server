<?php

namespace Tests\Feature\Api;

use App\Team;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tymon\JWTAuth\Facades\JWTAuth;

class TeamControllerTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();
        JWTAuth::shouldReceive('parseToken->authenticate')
            ->andReturn(factory(User::class)->create());
    }

    /** @test */
    public function it_stores_a_new_team()
    {
        $response = $this->post('/api/teams', ['name' => 'My Team Name']);

        $response->assertStatus(200)
                 ->assertJson(['data' => ['team' => ['name' => 'My Team Name']]]);
    }

    /** @test */
    public function it_returns_an_error_when_no_name_is_provided()
    {
        $response = $this->post('/api/teams', ['name' => '']);

        $response->assertStatus(400)
                 ->assertJson(['errors' => ['title' => 'Invalid data']]);
    }

    /** @test */
    public function it_returns_data_for_a_given_team()
    {
        $team = factory(Team::class)->create();

        $response = $this->get('/api/teams/' . $team->uid);

        $response->assertStatus(200)
                 ->assertJson(['data' => ['team' => $team->toArray()]]);
    }
}
