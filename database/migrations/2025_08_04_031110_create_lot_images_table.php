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
        Schema::create('lot_images', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('lot_id'); 
            $table->string('image');  
            $table->string('medium_image')->nullable();  
            $table->string('thumbnail_image')->nullable(); 
            $table->string('img_alt')->nullable();  
            $table->string('img_class')->nullable();  
            $table->integer('order')->default(0); 
            $table->timestamps();  

            // Foreign key constraint
            $table->foreign('lot_id')->references('id')->on('lots')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lot_images');
    }
};
