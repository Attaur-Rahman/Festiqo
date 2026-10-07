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
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            // Event category
            $table->foreignId('category_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Basic details
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description');
            $table->longText('rules')->nullable();

            // Location
            $table->string('venue');

            // Schedule
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time');

            // Registration
            $table->dateTime('registration_deadline');
            $table->unsignedInteger('max_participants');
            $table->decimal('registration_fee', 10, 2)->default(0);

            // Banner
            $table->string('banner')->nullable();

            // Status
            $table->enum('status', [
                'draft',
                'published',
                'completed',
                'cancelled',
            ])->default('draft');

            // Audit
            $table->uuid('created_by');

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->uuid('updated_by')->nullable();

            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->timestamps();

            $table->softDeletes();

            // Indexes
            $table->index('category_id');
            $table->index('event_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
