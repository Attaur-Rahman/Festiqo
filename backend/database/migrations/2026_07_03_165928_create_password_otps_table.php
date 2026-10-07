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
        Schema::create('password_otps', function (Blueprint $table) {
            $table->uuid('user_id')->unique();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            // Store hashed OTP
            $table->string('otp');

            // Number of verification attempts
            $table->unsignedTinyInteger('resend_attempts')->default(0);

            // OTP expiration
            $table->timestamp('expires_at');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_otps');
    }
};
