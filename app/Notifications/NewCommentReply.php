<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommentReply extends Notification implements ShouldQueue
{
    use Queueable;

    public Comment $reply;

    /**
     * Create a new notification instance.
     */
    public function __construct(Comment $reply)
    {
        $this->reply = $reply;
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
        $postUrl = route('posts.index') . '#post-' . $this->reply->post_id;

        return (new MailMessage)
                    ->subject('You have a new reply!')
                    ->greeting('Hello, ' . $notifiable->name . '!')
                    ->line($this->reply->user->name . ' has replied to your comment.')
                    ->line('"' . $this->reply->content . '"')
                    ->action('View Post', $postUrl)
                    ->line('Thank you for being a part of our community!');
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