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
        Schema::create('displayed_informations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('bio')->default(true);
            $table->boolean('player_experiences')->default(true);
            $table->boolean('awards')->default(true);
            $table->boolean('skills')->default(true);
            $table->boolean('languages')->default(true);
            $table->boolean('onboarding')->default(true);
            $table->boolean('education')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('displayed_informations', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('displayed_informations');
    }
};
