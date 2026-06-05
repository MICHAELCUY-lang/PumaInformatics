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
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voting_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Verified users only
            
            $table->string('ip_hash');
            $table->string('fingerprint_hash');
            
            // Note: NO softDeletes() here to ensure ledger immutability
            $table->timestamps();
            
            // Critical: Strict DB-level prevention of double voting
            $table->unique(['voting_session_id', 'user_id'], 'unique_vote_per_session');
            
            // Additional indexes for analytics
            $table->index('fingerprint_hash');
            $table->index('ip_hash');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
