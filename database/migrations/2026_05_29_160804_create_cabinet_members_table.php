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
        Schema::create('cabinet_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->nullable()->constrained('cabinet_departments')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('role_title'); // e.g. President, Vice President
            $table->integer('role_hierarchy_level')->default(100); // lower = higher rank
            $table->string('term_year'); // e.g. 2026-2027
            $table->boolean('is_active')->default(true);
            
            $table->text('biography')->nullable();
            $table->json('achievements')->nullable();
            $table->json('social_links')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabinet_members');
    }
};
