<?php
// database/migrations/2024_01_01_000010_create_member_notifications_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Enhanced member_notifications table
Schema::create('member_notifications', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Recipient
    $table->foreignId('sender_id')->nullable()->constrained('users')->nullOnDelete(); // Who sent it
    $table->string('title');
    $table->text('message');
    $table->string('type'); // 'admin_message', 'loan_update', 'savings_update', 'meeting', 'system'
    $table->json('data')->nullable();
    $table->timestamp('read_at')->nullable();
    $table->timestamps();

    $table->index(['user_id', 'read_at']);
    $table->index(['sender_id']);
});
    }

    public function down(): void
    {
        Schema::dropIfExists('member_notifications');
    }
};
