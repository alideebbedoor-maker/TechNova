<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // كاتب التعليق
            $table->text('body');
            
            // الأعمدة السحرية للـ Polymorphic (تنشئ تلقائياً: commentable_id و commentable_type مع الفهرسة)
            $table->morphs('commentable'); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};