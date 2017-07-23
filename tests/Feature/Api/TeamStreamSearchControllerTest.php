<?php

namespace Tests\Feature\Api;

use App\Team;
use App\Stream;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TeamStreamSearchControllerTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /** @test */
    public function it_returns_team_streams_for_a_given_term_for_a_team_member()
    {
        $team = factory(Team::class)->create();
        $team->members()->attach($this->authUser());

        $streams = factory(Stream::class, 2)->create([
            'team_id' => $team->id,
            'name' => 'Test ' . str_random(),
        ]);

        $this->post("/api/teams/{$team->uid}/streams/search", ['q' => 'test'])
             ->assertStatus(200)
             ->assertJson(['data' => ['streams' => $streams->toArray()]]);
    }

    /** @test */
    public function it_does_not_allow_non_team_members_to_search_another_teams_streams()
    {
        $this->authUser();

        $team = factory(Team::class)->create();

        factory(Stream::class, 2)->create([
            'team_id' => $team->id,
            'name' => 'Test ' . str_random(),
        ]);

        $this->post("/api/teams/{$team->uid}/streams/search", ['q' => 'test'])->assertStatus(403);
    }
}
