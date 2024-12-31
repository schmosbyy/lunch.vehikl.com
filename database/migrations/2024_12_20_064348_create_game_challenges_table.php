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
        Schema::create('game_challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenger_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('challenged_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('rsvp_id')->constrained('rsvps')->onDelete('cascade');
            $table->string('game_type');
            $table->enum('status', ['pending', 'accepted', 'declined', 'completed'])->default('pending');
            $table->string('game_url')->nullable();
            $table->timestamps();

            // Ensure a user can only have one active challenge with another user per RSVP and game type
            $table->unique(['challenger_id', 'challenged_id', 'rsvp_id', 'game_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_challenges');
    }
};
