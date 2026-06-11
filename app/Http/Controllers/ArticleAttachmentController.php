<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate; 

class ArticleAttachmentController extends Controller
{
    /**
     * Store a newly created attachment for a specific article.
     */
    public function store(Request $request, Article $article): JsonResponse
    {
        // 🔐 1. التحقق من الصلاحيات عبر الـ Policy (تأكد أن الـ Writer يملك المقال)
          Gate::authorize('update', $article);
        // 📝 2. التحقق من صحة الملف المرفوع وقواعد الـ Validation
        $request->validate([
            'file' => 'required|file|max:2048', // الحد الأقصى 2 ميجابايت للملف
        ]);

        // 3. معالجة وتخزين الملف
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // حفظ الملف في الـ Disk المسمى 'public' داخل مجلد 'attachments'
            $path = $file->store('attachments', 'public');

            // 🔥 4. الحفظ في قاعدة البيانات باستخدام علاقة الـ Polymorphic (attachable)
            $attachment = $article->attachments()->create([
                'file_path' => $path,
                'file_type' => $file->getClientMimeType(), // يجلب نوع الملف مثل application/pdf أو image/png
            ]);

            // 🚀 5. إرجاع استجابة بكود 201 طبقاً لشروط الكراسة
            return response()->json([
                'message' => 'Attachment uploaded successfully.',
                'data' => $attachment
            ], 201);
        }

        return response()->json(['message' => 'File not found.'], 400);
    }
}