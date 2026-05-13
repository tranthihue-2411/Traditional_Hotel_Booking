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
        Schema::create('rooms', function (Blueprint $table) {

            $table->id();

            $table->foreignId('hotel_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('name');

            $table->text('description')->nullable();

            $table->string('room_type')->nullable();

            $table->integer('max_guests');

            $table->decimal('size_sqm', 8, 2)->nullable();

            $table->string('bed_type')->nullable();

            $table->decimal('price_per_night', 10, 2);

            $table->integer('total_rooms')->default(1);

            $table->string('image')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};