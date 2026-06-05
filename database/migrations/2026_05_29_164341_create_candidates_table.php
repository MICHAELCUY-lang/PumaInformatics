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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voting_session_id')->constrained()->cascadeOnDelete();
            
            $table->string('name');
            $table->string('slug')->unique();
            
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->text('biography')->nullable();
            $table->text('achievements')->nullable();
            
            $table->json('social_links')->nullable();
            
            $table->integer('order')->default(0);
            $table->boolean('is_featured')->default(false);
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
