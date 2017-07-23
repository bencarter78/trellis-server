<?php

namespace Tests\Feature\Api;

use App\User;
use App\Stream;
use App\Project;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TeamStreamControllerTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /** @test */
    public function it_returns_all_streams_for_a_given_team()
    {
        $user = factory(User::class)->create();
        JWTAuth::shouldReceive('parseToken->authenticate')->andReturn($user);
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
