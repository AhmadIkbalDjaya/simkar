<?php

namespace App\Models;

use App\Enums\RoomStatus;
use Database\Factories\RoomFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['block_id', 'code', 'name', 'capacity', 'current_occupancy', 'status'])]
class Room extends Model
{
    /** @use HasFactory<RoomFactory> */
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'status' => RoomStatus::class,
        ];
    }

    public function block(): BelongsTo
    {
        return $this->belongsTo(Block::class);
    }

    public function inmates(): HasMany
    {
        return $this->hasMany(Inmate::class, 'current_room_id');
    }

    public function transfersFrom(): HasMany
    {
        return $this->hasMany(RoomTransfer::class, 'room_from_id');
    }

    public function transfersTo(): HasMany
    {
        return $this->hasMany(RoomTransfer::class, 'room_to_id');
    }
}
