<?php

use App\Enums\RoomStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('block_id')->constrained('blocks')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('code')->unique();
            $table->string('name')->nullable();
            $table->integer('capacity')->default(0);
            $table->integer('current_occupancy')->default(0);
            $table->string('status')->default(RoomStatus::Active->value);
            $table->timestamps();
            $table->softDeletes();
            $table->index('status');
            $table->index('current_occupancy');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
