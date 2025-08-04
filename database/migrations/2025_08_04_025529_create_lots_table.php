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
        Schema::create('lots', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('slug');
            $table->text('description')->nullable();
            $table->string('status');
            $table->boolean('featured')->default(0);

            $table->text('public_address')->nullable();
            $table->text('real_address')->nullable();
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->text('neighborhood')->nullable();

            $table->text('frontage')->nullable(); // frente
            $table->text('depth')->nullable();    // fondo
            $table->text('total_area')->nullable(); 

            $table->text('services')->nullable();

            $table->text('price')->nullable();
            $table->text('transaction_type')->nullable();

            $table->text('video')->nullable();
            $table->text('external_url')->nullable();

            $table->boolean('is_in_gated_community')->default(false); // está en un barrio cerrado

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
        Schema::dropIfExists('lots');
    }
};
