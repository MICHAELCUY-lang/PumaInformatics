<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        // Add cabinet_id column
        Schema::table('cabinet_members', function (Blueprint $table) {
            $table->unsignedBigInteger('cabinet_id')->nullable()->after('term_year');
            $table->foreign('cabinet_id')
                  ->references('id')
                  ->on('cabinets')
                  ->onDelete('set null');
        });

        // Populate cabinet_id based on existing term_year values
        $cabinets = DB::table('cabinets')->get();
        foreach ($cabinets as $cab) {
            DB::table('cabinet_members')
                ->where('term_year', $cab->term_year)
                ->update(['cabinet_id' => $cab->id]);
        }
    }

    public function down(): void
    {
        Schema::table('cabinet_members', function (Blueprint $table) {
            $table->dropForeign(['cabinet_id']);
            $table->dropColumn('cabinet_id');
        });
    }
};
?>
