<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the old table and recreate it with the new status options
        Schema::dropIfExists('rsvps');
        
        Schema::create('rsvps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('lunch_date');
            $table->string('status')->default('attending')->check("status IN ('attending', 'not_attending', 'out_of_town')");
            $table->timestamps();

            // Ensure a user can only RSVP once for a specific date
            $table->unique(['user_id', 'lunch_date']);
        });
    }

    public function down(): void
    {
        // Drop the table and recreate it with the original schema
        Schema::dropIfExists('rsvps');
        
        Schema::create('rsvps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('lunch_date');
            $table->string('status')->default('attending')->check("status IN ('attending', 'not_attending')");
            $table->timestamps();

            // Ensure a user can only RSVP once for a specific date
            $table->unique(['user_id', 'lunch_date']);
        });
    }
}; 