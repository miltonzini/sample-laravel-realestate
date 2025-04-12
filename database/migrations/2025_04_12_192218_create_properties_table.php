<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('slug');
            $table->text('description')->nullable();
            $table->text('property_type')->nullable();
            $table->string('status');
            $table->boolean('featured')->default(0);
            $table->text('public_address')->nullable();
            $table->text('real_address')->nullable();
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->text('neighborhood')->nullable();
            $table->integer('rooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->text('covered_area')->nullable();
            $table->text('total_area')->nullable();
            $table->text('width')->nullable();
            $table->text('length')->nullable();
            $table->text('orientation')->nullable();
            $table->text('position')->nullable();
            $table->integer('year_built')->nullable();
            $table->boolean('storage_room')->default(false);
            $table->text('services')->nullable();
            $table->text('heating_type')->nullable();
            $table->text('amenities')->nullable();
            
            $table->text('price')->nullable();
            $table->text('transaction_type')->nullable();
            $table->text('hoa_fees')->nullable();
            
            $table->text('video')->nullable();
            $table->text('external_url')->nullable();
            $table->text('private_notes')->nullable();
            $table->text('seller_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('properties');
    }
};