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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('to')->constrained('users')->onDelete('cascade');
            $table->foreignId('from')->constrained('users')->onDelete('cascade');
            $table->string('description');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['to']);
        });
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['from']);
        });
        Schema::dropIfExists('notifications');
    }
};
