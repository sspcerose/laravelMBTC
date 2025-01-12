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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->integer('tariff_id');
            $table->string('passenger');
            $table->string('location');
            $table->string('destination');
            $table->string('receipt');
            $table->string('time');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('price');
            $table->integer('remaining');
            $table->string('status');
            
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
