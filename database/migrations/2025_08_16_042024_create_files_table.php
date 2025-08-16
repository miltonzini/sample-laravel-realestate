<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            
            // Polymorphic relationship
            $table->morphs('parent'); // Creates parent_type and parent_id columns with automatic index
            
            // File information
            $table->string('file_path'); 
            $table->string('file_name'); 
            $table->string('original_name');
            $table->string('file_type', 10);
            $table->string('mime_type', 100)->nullable();
            $table->bigInteger('file_size')->nullable();
            
            $table->string('button_text'); 
            $table->text('description')->nullable(); 
            $table->integer('order')->default(0); 
            $table->boolean('is_public')->default(true); 
            
            $table->timestamps();


            $table->index('file_type');
            $table->index(['parent_type', 'parent_id', 'order']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('files');
    }
};