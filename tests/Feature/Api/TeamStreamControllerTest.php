<?php

namespace Tests\Feature\Api;

use App\Stream;
use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class TeamStreamControllerTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    /** @test */
    public function it_returns_all_streams_for_a_given_team()
    {
        $this->authUser();
        $project = factory(Project::class)->create();
        $streams = factory(Stream::class, 2)->create([
            'team_id' => $project->team_id,
            'project_id' => $project->id,
        ]);

        $this->get("/api/teams/{$project->team->uid}/streams")
             ->assertStatus(200)
             ->assertJson(['data' => ['streams' => $streams->toArray()]]);
    }
}
