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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete(); 
            $table->enum('payment_method',['cash','visa']);
            $table->enum('status',['pending','completed','failed','refunded'])->default('pending');
            $table->decimal('amount',10,2); 
            $table->softDeletes(); 
            $table->index(['payment_method']); 
            $table->index(['status']); 
            $table->index(['amount']);
            $table->string('transaction_id')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};