<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('aspirations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('aspiration_categories')->nullOnDelete();
            
            $table->string('subject');
            $table->text('payload');
            
            // Core States
            $table->string('status')->default('pending'); // pending, under_review, responded, resolved, archived, rejected
            $table->string('visibility')->default('private'); // private, public, featured
            $table->boolean('is_anonymous')->default(false);
            
            // Moderation
            $table->text('admin_notes')->nullable();
            $table->string('ip_hash')->nullable()->index();
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aspirations');
    }
};
