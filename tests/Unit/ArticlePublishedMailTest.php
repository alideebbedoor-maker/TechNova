<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Mail\ArticlePublishedMail;
use App\Models\Article;
use App\Models\User;

class ArticlePublishedMailTest extends TestCase
{
    public function test_mail_has_correct_subject_and_recipient()
    {
        // 1. تجهيز البيانات في الذاكرة (بدون حفظ في الداتابيز)
        $writer = new User(['email' => 'writer@technova.com', 'name' => 'Writer Name']);
        $article = new Article(['title' => 'Test Article Title']);
        $article->setRelation('author', $writer); // ربط العلاقة في الذاكرة

        // 2. بناء كائن الإيميل
        $mail = new ArticlePublishedMail($article);

        // 3. التحقق من الـ Subject
        $this->assertEquals('Your Article Has Been Published!', $mail->envelope()->subject);

        // 4. التحقق من أن الإيميل موجه للكاتب الصحيح (Recipient)
        $this->assertTrue($mail->hasTo('writer@technova.com'));
    }
}