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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name') ; 
            $table->string('slug')->unique() ; 
            $table->string('description');  
            $table->string('image'); 
            $table->boolean('status')->default(true) ; 
            $table->softDeletes(); 
            $table->index(['name']); 
            $table->index(['slug']); 
            $table->index(['description']); 
            $table->index(['status']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};