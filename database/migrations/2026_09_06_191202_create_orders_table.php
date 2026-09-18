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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('status',['success','failed']); 
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); 
            $table->foreignId('coupon_id')->constrained()->nullable()->cascadeOnDelete(); 
            $table->decimal('total',10,2); 
            $table->enum('payment_status',['pending','success','failed','refunded']);
            $table->decimal('shipping_const',10,2)->default(0);
            $table->decimal('tax') ; 
            $table->decimal('grand_total'); 
            $table->softDeletes() ; 
            $table->index(['name']); 
            $table->index(['status']);
            $table->index(['total']); 
            $table->index(['payment_status']); 
            $table->index(['shipping_const']);
            $table->index(['tax']);
            $table->index(['grand_total']); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};