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
        Schema::create('developments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('slug');
            $table->text('description');
            $table->string('property_type');
            $table->string('status');
            $table->boolean('featured')->default(false);
            $table->string('public_address')->nullable();
            $table->text('real_address')->nullable();
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->string('neighborhood')->nullable();
            $table->text('estimated_delivery_date')->nullable();
            $table->string('project_status');
            $table->string('developer')->nullable();
            
            $table->string('price_range')->nullable();
            $table->text('services')->nullable();
            $table->text('amenities')->nullable();
            $table->text('video')->nullable();  
            $table->string('external_url')->nullable();
            $table->text('private_notes')->nullable(); 
            $table->text('seller_notes')->nullable();  
            $table->timestamps();  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('developments');
    }
};