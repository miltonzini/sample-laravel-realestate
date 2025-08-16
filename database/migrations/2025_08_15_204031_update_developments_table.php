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
        Schema::table('developments', function (Blueprint $table) {
            $table->text('project_details')->nullable()->after('description');
            
            $table->dropColumn('property_type');
            $table->string('development_type')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('developments', function (Blueprint $table) {
            $table->dropColumn('development_type');

            $table->string('property_type')->after('project_details');
            $table->dropColumn('project_details');
        });
    }
};
