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
        Schema::create('examiners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('employee_id')->unique();
            $table->string('email')->nullable();
            $table->string('status')->default('Active');
            $table->timestamps();
        });

        Schema::table('examiners', function (Blueprint $table) {
            $table->index('name');
            $table->index('employee_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examiners');
    }
};
