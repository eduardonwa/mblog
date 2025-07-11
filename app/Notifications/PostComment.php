<?php

namespace App\Notifications;

use App\Traits\InteractsWithPost;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostComment extends Notification
{
    use Queueable, InteractsWithPost;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public \App\Models\CustomComment $comment
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
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("One of your posts received a comment")
            ->greeting("Hey, {$notifiable->username},")
            ->line("{$this->comment->commentator->username} commented on your post")
            ->line("“{$this->comment->comment}”")
            ->action('See post', $this->generatePostUrl($this->comment->commentable))
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
            'message' => "{$this->comment->commentator->username} commented on your post: {$this->comment->commentable->title}",
            'url' => $this->generatePostUrl($this->comment->commentable),
        ];
    }
}
