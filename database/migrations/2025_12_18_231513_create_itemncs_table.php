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
        Schema::create('itemncs', function (Blueprint $table) {
            $table->id();
            $table->string('item12nc')->unique();
            $table->string('partname');
            $table->string('type')->nullable();
            $table->integer('quantity')->default(0);
            $table->string('unit')->default('Pcs');
            $table->timestamps();  
        });

        Schema::table('itemncs', function (Blueprint $table) {
            $table->index('item12nc');
            $table->index('partname');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itemncs');
    }
};
