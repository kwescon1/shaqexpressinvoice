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
        Schema::create('sold_products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    // Schema::create('sold_inventory_items', function (Blueprint $table) {
    //     $table->bigInteger('id')->primary()->unsigned();
    //     $table->foreignId('facility_id')->constrained('facilities')->onUpdate('cascade')->onDelete('cascade');
    //     $table->foreignId('facility_branch_id')->constrained('facility_branches')->onUpdate('cascade')->onDelete('cascade');
    //     $table->foreignId('receipt_id')->nullable()->constrained('receipts')->onUpdate('cascade')->onDelete('cascade');
    //     $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onUpdate('cascade')->onDelete('cascade');
    //     $table->foreignId('item_id')->constrained('inventory_items')->onUpdate('cascade')->onDelete('cascade');
    //     $table->decimal('price');
    //     $table->integer('quantity');
    //     $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
    // });

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sold_products');
    }
};
