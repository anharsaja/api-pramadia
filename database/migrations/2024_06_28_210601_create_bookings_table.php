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
            $table->unsignedBigInteger('user_id');
            $table->string('layanan');
            $table->string('no_medrek');
            $table->date('tgl_booking');
            $table->string('dokter');
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->string('biaya_layanan');
            $table->string('biaya_admin');
            $table->enum('status',['pending', 'done', 'cancel'])->default('pending');
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
