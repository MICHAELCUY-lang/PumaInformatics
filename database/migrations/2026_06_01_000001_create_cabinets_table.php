<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cabinets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('term_year')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
        // Populate cabinets from existing distinct term_year values and set first as active
        $termYears = \DB::table('cabinet_members')->distinct()->pluck('term_year')->filter()->toArray();
        foreach ($termYears as $term) {
            \DB::table('cabinets')->insert([
                'name' => $term,
                'slug' => \Str::slug($term),
                'term_year' => $term,
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        // Optionally set the most recent term as active
        $latest = \DB::table('cabinets')->orderBy('term_year', 'desc')->first();
        if ($latest) {
            \DB::table('cabinets')->where('id', $latest->id)->update(['is_active' => true]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cabinets');
    }
};
?>
