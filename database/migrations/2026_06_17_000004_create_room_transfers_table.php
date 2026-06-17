<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inmate_id')->constrained('inmates');
            $table->foreignId('room_from_id')->constrained('rooms');
            $table->foreignId('room_to_id')->constrained('rooms');
            $table->dateTime('transferred_at');
            $table->string('officer_name');
            $table->longText('officer_signature')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_transfers');
    }
};
