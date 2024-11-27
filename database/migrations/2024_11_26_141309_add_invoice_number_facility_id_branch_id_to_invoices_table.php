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
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('invoice_number', 17)->unique()->after('uuid'); // Add unique constraint and position after 'uuid'

            // Add foreign keys for facility_id and branch_id
            $table->foreignId('facility_id')->constrained('facilities')->cascadeOnDelete()->after('invoice_number');
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete()->after('facility_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['facility_id']);
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['facility_id', 'branch_id', 'invoice_number']);
        });
    }
};
