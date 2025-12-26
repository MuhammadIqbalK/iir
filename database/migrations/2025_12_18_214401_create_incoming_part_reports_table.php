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
        Schema::create('incoming_part_reports', function (Blueprint $table) {
            $table->id();
            $table->date('iirdate');
            $table->string('itemnc');
            $table->string('nodoc');
            $table->integer('quantity');
            $table->integer('samplesize');
            $table->integer('gilevel');
            $table->integer('examiner_id');
            $table->time('start');
            $table->time('end');
            $table->string('duration');
            $table->string('supplier_code');
            $table->string('option');
            $table->integer('batch')->default('1');
            $table->string('status');
            $table->timestamps();
        });

        Schema::table('incoming_part_reports', function (Blueprint $table) {
            $table->index('itemnc');
            $table->index('iirdate');
            $table->index('option');
            $table->index('supplier_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_part_reports');
        Schema::table('incoming_part_reports', function (Blueprint $table) {
            $table->dropIndex('itemnc');
            $table->dropIndex('iirdate');
            $table->dropIndex('option');
            $table->dropIndex('supplier_code');
        });
    }
};
