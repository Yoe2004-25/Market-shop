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
         Schema::create('coupon_user', function (Blueprint $table) {
            $table->id() ; 
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); 
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->dateTime('time_of_coupon') ;
            $table->index(['user_id', 'coupon_id']);
            $table->index(['time_of_coupon']);
            $table->unique(['user_id', 'coupon_id']);
            $table->timestamps();
         });
          
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};