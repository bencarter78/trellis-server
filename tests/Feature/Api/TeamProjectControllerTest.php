<?php

namespace Tests\Feature\Api;

use App\Team;
use App\User;
use App\Project;
use Carbon\Carbon;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TeamProjectControllerTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();
        JWTAuth::shouldReceive('parseToken->authenticate')->andReturn(factory(User::class)->create());
    }

    /** @test */
    public function it_creates_a_new_project_for_a_team()
    {
        $team = factory(Team::class)->create();

        $this->post("/api/teams/{$team->uid}/projects", [
            'name' => 'Demo Project',
            'description' => 'This is the description',
            'due_on' => Carbon::now()->addMonth()->format('Y-m-d'),
        ])
             ->assertStatus(200)
             ->assertJson(['data' => ['project' => ['name' => 'Demo Project']]]);
    }

    /** @test
    @small */
    public function it_returns_a_project_for_a_project_member()
    {
        $user = $this->authUser();

        $project = factory(Project::class)->create();
        $project->members()->attach($user->id);
        dd($project->members->first()->id, $user->id);

        $response = $this->get("/api/teams/{$project->team->uid}/projects/{$project->uid}");

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'project' => $project->toArray(),
                     ],
                 ]);
    }
}
