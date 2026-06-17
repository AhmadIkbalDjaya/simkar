<?php

namespace App\Models;

use Database\Factories\RoomFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'block', 'capacity', 'current_occupancy'])]
class Room extends Model
{
    /** @use HasFactory<RoomFactory> */
    use HasFactory;

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
