<?php

namespace Tests\Feature\Api;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectControllerTest extends TestCase
{
    /** @test */
    public function it_returns_all_projects_for_an_authenticated_user()
    {
        $response = $this->actingAs(factory(User::class)->create())->get('/api/v1/projects');
        $response->assertStatus(200);
    }
}
