<?php

use App\Enums\TransferStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_number')->unique();
            $table->foreignId('inmate_id')->constrained('inmates')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('room_from_id')->constrained('rooms')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('room_to_id')->constrained('rooms')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('reason')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('transferred_at');
            $table->string('status')->default(TransferStatus::Completed->value);
            $table->string('officer_name');
            $table->longText('officer_signature')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('transferred_at');
            $table->index('status');
            $table->index(['inmate_id', 'transferred_at']);
            $table->index(['room_to_id', 'transferred_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_transfers');
    }
};
