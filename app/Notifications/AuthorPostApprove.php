<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AuthorPostApprove extends Notification
{
    use Queueable;
    public $post;

    public function __construct($post)
    {
        $this->post=$post;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your post successfully approved')
            ->greeting('Hello, '. $this->post->user->name .'!')
            ->line('Your post has been successfully approved.')
            ->line('Post Title: '.$this->post->title)
            ->action('View', url(route('author.post.show',$this->post->id)))
            ->line('Thank you for using our service!');
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
