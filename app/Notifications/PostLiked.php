<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostLiked extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public \App\Models\User $liker,
        public \App\Models\Post $post
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Obtener la url del post dependiendo del rol del autor
     */
    protected function postUrl(): string
    {
        // member
        if ($this->post->channel) {
            return url("/channel/{$this->post->channel->slug}/{$this->post->slug}");
        }
        // admin, staff
        return url("/posts/{$this->post->slug}");
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("One of your posts received an 'uphail'")
            ->greeting("Hey, {$notifiable->username},")
            ->line("{$this->liker->username} uphailed your post")
            ->action('See post', $this->postUrl())
            ->line('Stay heavy.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => "{$this->liker->username} uphailed your post: {$this->post->title}",
            'url' => $this->postUrl(),
        ];
    }
}
