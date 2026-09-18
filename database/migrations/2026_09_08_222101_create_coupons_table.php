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
        
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name',225);
            $table->string('code',225)->unique(); 
            $table->enum('type',['fixed','percentage']); 
            $table->decimal('value',10,2);
            $table->dateTime('expire_date')->nullable(); 
            $table->integer('usage_limit')->default(1); 
            $table->enum('status',['active','nonactive'])->default('active');
            //soft Deletes  
            $table->softDeletes(); 
            //index
            $table->index(['name']);
            $table->index(['code']); 
            $table->index(['type']); 
            $table->index(['value']); 
            $table->index(['expire_date']); 
            $table->index(['usage_limit']); 
            $table->index(['status']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};