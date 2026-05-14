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

            $table->string('booking_reference')->unique();

            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('hotel_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('room_id')
                ->constrained()
                ->onDelete('cascade');

            $table->date('check_in_date');

            $table->date('check_out_date');

            $table->integer('number_of_guests');

            $table->integer('number_of_nights');

            $table->decimal('room_price_per_night', 10, 2);

            $table->decimal('subtotal', 10, 2);

            $table->decimal('service_fee', 10, 2)->default(0);

            $table->decimal('discount', 10, 2)->default(0);

            $table->decimal('total_amount', 10, 2);

            $table->string('guest_name');

            $table->string('guest_email');

            $table->string('guest_phone');

            $table->text('special_requests')->nullable();

            $table->string('status')->default('pending');

            $table->timestamp('cancelled_at')->nullable();

            $table->text('cancellation_reason')->nullable();

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