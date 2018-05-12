<?php

namespace Tests\Feature\Api;

use App\Team;
use App\Project;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class TeamProjectControllerTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    /** @test */
    public function a_team_member_can_create_a_new_project_for_a_team()
    {
        $user = $this->authUser();

        $team = factory(Team::class)->create();
        $team->members()->attach($user->id);

        $this->post("/api/teams/{$team->uid}/projects", [
            'name' => 'Demo Project',
            'description' => 'This is the description',
            'due_on' => Carbon::now()->addMonth()->format('Y-m-d'),
        ])
             ->assertStatus(200)
             ->assertJson(['data' => ['project' => ['name' => 'Demo Project']]]);
    }

    /** @test */
    public function it_returns_a_project_for_a_project_member()
    {
        $user = $this->authUser();

        $project = factory(Project::class)->create();
        $project->members()->attach($user->id);

        $response = $this->get("/api/teams/{$project->team->uid}/projects/{$project->uid}");

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'project' => $project->toArray(),
                     ],
                 ]);
    }
}
