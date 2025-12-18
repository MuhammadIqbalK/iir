<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('supplierName');
            $table->string('supplierCode')->unique();
            $table->string('contactPerson');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->string('status');
            $table->timestamps();
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->index('supplierCode');
            $table->index('supplierName');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropIndex('supplierCode');
            $table->dropIndex('supplierName');
        });
    }
};
