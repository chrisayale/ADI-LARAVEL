<?php
// database/migrations/2024_01_01_000008_create_meeting_notices_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meeting_notices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->date('meeting_date');
            $table->time('meeting_time');
            $table->string('venue');
            $table->string('agenda')->nullable();
            $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meeting_notices');
    }
};
