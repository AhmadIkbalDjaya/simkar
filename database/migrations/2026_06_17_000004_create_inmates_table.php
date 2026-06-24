<?php

use App\Enums\InmateStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inmates', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->string('name');
            $table->string('gender')->nullable();
            $table->string('crime_type')->nullable();
            $table->date('admission_date')->nullable();
            $table->date('placement_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->string('status')->default(InmateStatus::Active->value);
            $table->foreignId('current_room_id')->nullable()->constrained('rooms')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('name');
            $table->index('status');
            $table->index('admission_date');
            $table->index('expiration_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inmates');
    }
};
