<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\SendInvitation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class SendInvitationToJoinLoLLFT implements ShouldQueue
{
    use Queueable;

    public string $email;
    public string $username;
    public User $user;

    /**
     * Create a new job instance.
     */
    public function __construct($email,  $username, $user)
    {
        $this->email = $email;
        $this->username = $username;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
            Notification::route('mail', $this->email)->notify(new SendInvitation($this->email, $this->username, $this->user));
    }
}
