<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_id')->nullable();
            $table->unsignedBigInteger('team_id');
            $table->string('username')->nullable();
            $table->string('type')->nullable();
            $table->string('nationality')->nullable();
            $table->string('job')->nullable();
            $table->date('entry_date')->nullable();
            $table->boolean('archived')->nullable();
            $table->timestamps();

            $table->foreign('player_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->dropForeign(['player_id']);
            $table->dropForeign(['team_id']);
        });
        Schema::dropIfExists('team_members');
    }
};
