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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->date('tgl_lahir');
            $table->enum('title_pasien', ['nn', 'tn']);
            $table->enum('status_kawin', ['belum', 'sudah']);
            $table->string('tempat_lahir');
            $table->enum('jenis_kelamin', ['laki', 'perempuan']);
            $table->string('alamat');
            $table->string('provinsi');
            $table->string('kabupaten');
            $table->string('kecamatan');
            $table->string('kelurahan');
            $table->string('foto_ktp');
            $table->string('no_ktp')->unique();
            $table->string('pekerjaan');
            $table->string('pendidikan');
            $table->string('agama');
            $table->string('no_telepon')->unique();
            $table->enum('pelayanan', ['sudah', 'belum']);
            $table->string('nama_keluarga');
            $table->string('no_telepon_keluarga')->unique();
            $table->string('alamat_keluarga');
            $table->string('hubungan_keluarga');
            $table->string('password');

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
