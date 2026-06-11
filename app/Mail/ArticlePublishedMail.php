<?php

namespace App\Mail;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ArticlePublishedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $article;

    /**
     * Create a new message instance.
     */
    public function __construct(Article $article)  
    {
        $this->article = $article;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
{
    return new Envelope(
        subject: 'Your Article Has Been Published!',
        to: $this->article->author->email, // تأكدي أننا نمرر الإيميل هنا
    );
}

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.published',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}