<?php

namespace Tests\Feature\APi;

use App\Stream;
use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ProjectStreamControllerTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    /** @test */
    public function it_returns_all_streams_for_a_project_member()
    {
        $user = $this->authUser();
        $project = factory(Project::class)->create();
        $project->members()->attach($user);
        $streams = factory(Stream::class, 2)->create(['project_id' => $project->id]);

        $this->get("/api/projects/{$project->uid}/streams")
             ->assertStatus(200)
             ->assertJson(['data' => ['streams' => $streams->toArray()]]);
    }

    /** @test */
    public function a_project_owner_can_create_a_new_stream_for_a_project()
    {
        $user = $this->authUser();

        $project = factory(Project::class)->create(['owner_id' => $user->id]);

        $stream = factory(Stream::class)->make(['project_id' => $project->id]);

        $this->post("/api/projects/{$project->uid}/streams", [
            'owner_id' => $user->username,
            'name' => $stream->name,
        ])
             ->assertStatus(200)
             ->assertJson(['data' => ['stream' => ['name' => $stream->name]]]);
    }

    /** @test */
    public function it_aborts_when_a_user_is_not_the_project_owner()
    {
        $user = $this->authUser();

        $stream = factory(Stream::class)->make();

        $this->post("/api/projects/{$stream->project->uid}/streams", [
            'owner_id' => $user->id,
            'name' => $stream->name,
        ])
             ->assertStatus(403);
    }

    /** @test */
    public function it_returns_a_given_stream_for_a_project_owner()
    {
        $user = $this->authUser();
        $project = factory(Project::class)->create(['owner_id' => $user->id]);
        $stream = factory(Stream::class)->create(['project_id' => $project->id]);

        $this->get("/api/projects/{$project->uid}/streams/{$stream->uid}")
             ->assertStatus(200)
             ->assertJson(['data' => ['stream' => $stream->toArray()]]);
    }

    /** @test */
    public function it_returns_a_given_stream_for_a_project_member()
    {
        $user = $this->authUser();
        $project = factory(Project::class)->create();
        $project->members()->attach($user->id);
        $stream = factory(Stream::class)->create(['project_id' => $project->id]);

        $this->get("/api/projects/{$project->uid}/streams/{$stream->uid}")
             ->assertStatus(200)
             ->assertJson(['data' => ['stream' => $stream->toArray()]]);
    }

    /** @test */
    public function it_removes_a_stream_from_a_project()
    {
        $user = $this->authUser();
        $project = factory(Project::class)->create(['owner_id' => $user->id]);
        $stream = factory(Stream::class)->create(['project_id' => $project->id]);

        $this->delete("/api/projects/{$project->uid}/streams/{$stream->uid}")
             ->assertStatus(200);
    }
}
