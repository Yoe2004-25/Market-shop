<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();  
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete(); 
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();        
            $table->string('name'); 
            $table->string('slug')->unique() ; 
            $table->text('description');
            $table->decimal('price');
            $table->integer('stock')->default(1); 
            $table->decimal('discount', 10, 2)->default(0);  
            $table->string('sku', 50)->unique();             
            $table->string('image')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');  
            $table->string('image');
            $table->softDeletes(); 
            $table->index(['name']);
            $table->index(['slug']); 
            $table->index(['description']); 
            $table->index(['price']); 
            $table->index(['stock']); 
            $table->index(['sku']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};