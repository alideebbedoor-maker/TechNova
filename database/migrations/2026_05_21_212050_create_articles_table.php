<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('articles', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('content');
        
        $table->string('status')->default('draft')->index(); 
        
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
        
        $table->timestamp('published_at')->nullable()->index(); 
    
        $table->timestamps();
        $table->softDeletes();

    });
}

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};