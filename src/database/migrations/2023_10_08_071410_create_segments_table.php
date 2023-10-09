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
        Schema::create('segments', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('schedule_id')->index();
            $table->integer('sequence_number');
            $table->integer('remaining_amount');
            $table->integer('principal_payment');
            $table->integer('interest_payment');
            $table->integer('euribor_payment');
            $table->integer('total_payment');
            $table->integer('euribor_rate');
            $table->timestamps();

            $table->unique(['schedule_id', 'sequence_number']);
            $table->foreign('schedule_id')->references('id')->on('schedules')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('segments');
    }
};
