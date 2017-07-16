<?php

namespace Tests\Feature\APi;

use App\User;
use App\Stream;
use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Tymon\JWTAuth\Facades\JWTAuth;

class StreamControllerTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /** @test */
    public function it_returns_all_streams_for_a_project()
    {
        $user = factory(User::class)->create();
        JWTAuth::shouldReceive('parseToken->authenticate')->andReturn($user);
        $project = factory(Project::class)->create();
        $streams = factory(Stream::class, 2)->create(['project_id' => $project->id]);

        $this->get("/api/projects/{$project->uid}/streams")
             ->assertStatus(200)
             ->assertJson(['data' => ['streams' => $streams->toArray()]]);
    }

    /** @test */
    public function it_aborts_when_a_user_is_not_a_member_of_the_project()
    {
        $this->markTestSkipped();
    }

    /** @test */
    public function it_stores_a_new_stream_for_a_project()
    {
        $user = factory(User::class)->create();
        JWTAuth::shouldReceive('parseToken->authenticate')->andReturn($user);
        $project = factory(Project::class)->create(['owner_id' => $user->id]);
        $stream = factory(Stream::class)->make(['project_id' => $project->id]);

        $this->post("/api/projects/{$project->uid}/streams", [
            'project_id' => $stream->project_uid,
            'owner_id' => $stream->owner_id,
            'name' => $stream->name,
        ])
             ->assertStatus(200)
             ->assertJson(['data' => ['objective' => ['name' => $stream->name]]]);
    }

    /** @test */
    public function it_aborts_when_a_user_is_not_the_project_owner()
    {
        $user = factory(User::class)->create();
        JWTAuth::shouldReceive('parseToken->authenticate')->andReturn($user);
        $stream = factory(Stream::class)->make();

        $this->post("/api/projects/{$stream->project->uid}/streams", [
            'project_id' => $stream->project_id,
            'owner_id' => $stream->owner_id,
            'name' => $stream->name,
        ])
             ->assertStatus(403);
    }

    /** @test */
    public function it_removes_an_objective_from_a_project()
    {
        $user = factory(User::class)->create();
        JWTAuth::shouldReceive('parseToken->authenticate')->andReturn($user);
        $project = factory(Project::class)->create(['owner_id' => $user->id]);
        $stream = factory(Stream::class)->create(['project_id' => $project->id]);

        $this->delete("/api/projects/{$project->uid}/streams/{$stream->id}")
             ->assertStatus(200);
    }
}
