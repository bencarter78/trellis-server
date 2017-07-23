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

    /** @test */
    public function it_returns_all_teams_for_an_authenticated_user()
    {
        $user = factory(User::class)->create();

        JWTAuth::shouldReceive('parseToken->authenticate')->andReturn($user);

        $teams = factory(Team::class, 2)->create();
        $teams->each(function ($t) use ($user) {
            $t->members()->attach($user->id);
        });

        $this->get('/api/teams')
             ->assertStatus(200)
             ->assertJson(['data' => ['teams' => $teams->toArray()]]);
    }

    /** @test */
    public function it_stores_a_new_team()
    {
        JWTAuth::shouldReceive('parseToken->authenticate')->andReturn(factory(User::class)->create());

        $this->post('/api/teams', ['name' => 'My Team Name'])
             ->assertStatus(200)
             ->assertJson(['data' => ['team' => ['name' => 'My Team Name']]]);
    }

    /** @test */
    public function it_returns_an_error_when_no_name_is_provided()
    {
        JWTAuth::shouldReceive('parseToken->authenticate')->andReturn(factory(User::class)->create());

        $this->post('/api/teams', ['name' => ''])
             ->assertStatus(400)
             ->assertJson(['errors' => ['title' => 'Invalid data']]);
    }

    /** @test */
    public function it_returns_data_for_a_given_team()
    {
        JWTAuth::shouldReceive('parseToken->authenticate')->andReturn(factory(User::class)->create());

        $team = factory(Team::class)->create();

        $this->get('/api/teams/' . $team->uid)
             ->assertStatus(200)
             ->assertJson(['data' => ['team' => $team->toArray()]]);
    }
}
