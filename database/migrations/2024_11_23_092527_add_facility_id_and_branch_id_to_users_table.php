<?php

declare(strict_types=1);

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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('facility_id')->after('password')->constrained('facilities')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->after('facility_id')->constrained('branches')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['facility_id']);
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['facility_id', 'branch_id']);
        });
    }
};
