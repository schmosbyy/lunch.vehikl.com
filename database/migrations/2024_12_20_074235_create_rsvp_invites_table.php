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
        Schema::create('rsvp_invites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inviter_id')->constrained('users')->onDelete('cascade');
            $table->string('invitee_github_id');
            $table->string('invitee_github_username');
            $table->date('lunch_date');
            $table->boolean('is_accepted')->default(false);
            $table->timestamps();

            // Unique constraint to prevent duplicate invites
            $table->unique(['inviter_id', 'invitee_github_id', 'lunch_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rsvp_invites');
    }
};
