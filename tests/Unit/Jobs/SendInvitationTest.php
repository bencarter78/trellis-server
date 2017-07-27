<?php

namespace Tests\Unit\Jobs;

use Tests\TestCase;
use App\Jobs\SendInvitation;
use App\Mail\ProjectInvitation;

use App\Project;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SendInvitationTest extends TestCase
{
    /** @test **/
    public function it_sends_an_invitation()
    {
        Mail::fake();

        // $project = factory(Project::class)->create();

        (new SendInvitation('test@email.com', new Project()))->handle();

        Mail::assertSent(ProjectInvitation::class, function ($mail) {
            return $mail->to('test@email.com');
        });
    }
}
