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
            $table->foreignId('hotel_id')->constrained('hotels')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('room_type', ['Single', 'Double', 'Suite', 'Villa']);
            $table->unsignedInteger('max_guests');
            $table->decimal('size_sqm', 8, 2)->nullable();
            $table->string('bed_type', 100)->nullable();
            $table->decimal('price_per_night', 10, 2);
            $table->unsignedInteger('total_rooms');
            $table->json('amenities')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('hotel_id');
            $table->index('price_per_night');
            $table->index('is_active');
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