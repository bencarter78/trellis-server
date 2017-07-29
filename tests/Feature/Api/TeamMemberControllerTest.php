<?php

namespace Tests\Feature\Api;

use App\Team;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TeamMemberControllerTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /** @test */
    public function it_returns_all_members_of_a_team()
    {
        $user = $this->authUser();
        $team = factory(Team::class)->create();
        $members = factory(User::class, 2)->create();
        $team->members()->attach([$user->id, $members->first()->id, $members->last()->id]);

        $this->get("/api/teams/{$team->uid}/members")
             ->assertStatus(200)
             ->assertJson(['data' => ['members' => $team->members->toArray()]]);
    }

    /** @test */
    public function a_team_owner_can_remove_a_member_from_the_team()
    {
        $user = $this->authUser();
        $team = factory(Team::class)->create(['owner_id' => $user->id]);
        $member = factory(User::class)->create();
        $team->members()->attach($member->id);

        $this->delete("/api/teams/{$team->uid}/members/$member->id")
             ->assertStatus(200);
    }
}
