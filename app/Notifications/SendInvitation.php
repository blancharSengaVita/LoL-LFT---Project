<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendInvitation extends Notification
{
    use Queueable;

    public string $email;
    public string $username;
    public User $user;
    /**
     * Create a new notification instance.
     */
    public function __construct($email,  $username, $user)
    {
        $this->email = $email;
        $this->username = $username;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->user->username . ' vous invite à rejoindre son équipe')
            ->greeting('Bonjour ' .  $this->username . ',')
            ->line('Nous avons le plaisir de vous annoncer que ' . $this->user->username . ' vous invite à faire partie de son équipe')
            ->line('Vous pouvez maintenant accéder à la plateforme et vous inscrire dès maintenant.')
            ->action('Accéder à LoLLFT', url('/register'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
