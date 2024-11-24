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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }
    // Schema::create('orders', function (Blueprint $table) {
    //     $table->bigInteger('id')->primary()->unsigned();
    //     $table->foreignId('facility_id')->constrained('facilities')->onUpdate('cascade')->onDelete('cascade');
    //     $table->foreignId('facility_branch_id')->constrained('facility_branches')->onUpdate('cascade')->onDelete('cascade');
    //     $table->foreignId('client_id')->constrained('clients')->onUpdate('cascade')->onDelete('cascade');
    //     $table->foreignId('receipt_id')->nullable()->constrained('receipts')->onUpdate('cascade')->onDelete('cascade');
    //     $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onUpdate('cascade')->onDelete('cascade');
    //     $table->string('order_number', 20)->index();
    //     $table->date('order_date');
    //     $table->date('order_ready_date');
    //     $table->string('client_contact', 15)->nullable();
    //     $table->tinyInteger('use_client_phone')->default(0);
    //     $table->tinyInteger('status')->default(1);
    //     $table->tinyInteger('shipping_method');
    //     $table->foreignId('created_by')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
    //     $table->timestamps();
    //     $table->softDeletes();

    //     Schema::create('orders', function (Blueprint $table) {
    //         $table->bigInteger('id')->primary()->unsigned();
    //         $table->foreignId('facility_id')->constrained('facilities')->onUpdate('cascade')->onDelete('cascade');
    //         $table->foreignId('facility_branch_id')->constrained('facility_branches')->onUpdate('cascade')->onDelete('cascade');
    //         $table->foreignId('client_id')->constrained('clients')->onUpdate('cascade')->onDelete('cascade');
    //         $table->foreignId('receipt_id')->nullable()->constrained('receipts')->onUpdate('cascade')->onDelete('cascade');
    //         $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onUpdate('cascade')->onDelete('cascade');
    //         $table->string('order_number', 20)->index();
    //         $table->date('order_date');
    //         $table->date('order_ready_date');
    //         $table->string('client_contact', 15)->nullable();
    //         $table->tinyInteger('use_client_phone')->default(0);
    //         $table->tinyInteger('status')->default(1);
    //         $table->tinyInteger('shipping_method');
    //         $table->foreignId('created_by')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
    //         $table->timestamps();
    //         $table->softDeletes();

    //         $ $table->json('order_items')->after('order_ready_date')->required();

    //     $table->unique(['facility_id', 'facility_branch_id', 'order_number']);

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
