<?php

namespace App\Notifications;

use App\Models\CustomComment;
use App\Traits\InteractsWithPost;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CommentModeration extends Notification
{
    use InteractsWithPost;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public CustomComment $comment,
        public string $status // 'approved', 'disapproved'
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
        $message = (new MailMessage)
            ->greeting("Hey, {$notifiable->username}");
        
        if ($this->status === 'approved') {
            $message->subject('Your comment has been reinstated')
                ->line("Your comment on \"{$this->comment->commentable->title}\" has been reinstated: \"{$this->comment->comment}\"")
                ->action("View it again", $this->generatePostUrl($this->comment->commentable));
        } else {
            $message->subject('Your comment was removed')
                ->line("Your comment on \"{$this->comment->commentable->title}\" was removed: \"{$this->comment->comment}\"");
        }

        return $message->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $message = $this->status === 'approved'
            ? "Your comment on \"{$this->comment->commentable->title}\" was reinstated"
            : "Your comment on \"{$this->comment->commentable->title}\" was removed.";

        return [
            'message' => $message,
            'comment' => $this->comment->comment,
            'status' => $this->status,
            'url' => $this->status === 'approved'
                ? $this->generatePostUrl($this->comment->commentable) . "#comment-" . $this->comment->id
                : null
        ];
    }
}
