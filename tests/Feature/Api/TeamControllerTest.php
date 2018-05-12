<?php

namespace Tests\Feature\Api;

use App\Team;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class TeamControllerTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    /** @test */
    public function it_returns_all_teams_for_an_authenticated_user()
    {
        $user = $this->authUser();

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
        $this->authUser();

        $this->post('/api/teams', ['name' => 'My Team Name'])
             ->assertStatus(200)
             ->assertJson(['data' => ['team' => ['name' => 'My Team Name']]]);
    }

    /** @test */
    public function it_returns_an_error_when_no_name_is_provided()
    {
        $this->authUser();

        $this->post('/api/teams', ['name' => ''])->assertStatus(302);
    }

    /** @test */
    public function it_returns_data_for_a_given_team()
    {
        $this->authUser();

        $team = factory(Team::class)->create();

        $this->get('/api/teams/' . $team->uid)
             ->assertStatus(200)
             ->assertJson(['data' => ['team' => $team->toArray()]]);
    }
}
