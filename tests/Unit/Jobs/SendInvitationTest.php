<?php

namespace Tests\Unit\Jobs;

use App\Project;
use Tests\TestCase;
use App\Jobs\SendInvitation;
use App\Mail\ProjectInvitation;
use Illuminate\Support\Facades\Mail;

class SendInvitationTest extends TestCase
{
    /** @test **/
    public function it_sends_an_invitation()
    {
        Mail::fake();

        (new SendInvitation('test@email.com', new Project()))->handle();

        Mail::assertSent(ProjectInvitation::class, function ($mail) {
            return $mail->hasTo('test@email.com');
        });
    }
}
