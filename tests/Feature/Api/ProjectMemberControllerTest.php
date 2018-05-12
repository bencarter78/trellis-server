<?php

namespace Tests\Feature\Api;

use App\User;
use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ProjectMemberControllerTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    /** @test */
    public function it_returns_all_members_of_a_project()
    {
        $user = $this->authUser();
        $project = factory(Project::class)->create();
        $members = factory(User::class, 2)->create();
        $project->members()->attach([$user->id, $members->first()->id, $members->last()->id]);

        $this->get("/api/projects/{$project->uid}/members")
             ->assertStatus(200)
             ->assertJson(['data' => ['members' => $project->members->toArray()]]);
    }

    /** @test */
    public function a_project_owner_can_add_an_existing_team_member()
    {
        $user = $this->authUser();
        $project = factory(Project::class)->create(['owner_id' => $user->id]);
        $member = factory(User::class)->create();

        $this->post("/api/projects/{$project->uid}/members/", ['user_id' => $member->id])
             ->assertStatus(200)
             ->assertJson(['data' => ['members' => [$member->toArray()]]]);
    }

    /** @test */
    public function a_project_owner_can_remove_a_member_from_the_project()
    {
        $user = $this->authUser();
        $project = factory(Project::class)->create(['owner_id' => $user->id]);
        $member = factory(User::class)->create();
        $project->members()->attach($member->id);

        $this->delete("/api/projects/{$project->uid}/members/$member->id")
             ->assertStatus(200);
    }
}
