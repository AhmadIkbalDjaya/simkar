<?php

namespace App\Models;

use App\Enums\GenderType;
use Database\Factories\InmateFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['registration_number', 'name', 'gender', 'current_room_id'])]
class Inmate extends Model
{
    /** @use HasFactory<InmateFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'gender' => GenderType::class,
        ];
    }

    public function currentRoom(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'current_room_id');
    }

    public function roomTransfers(): HasMany
    {
        return $this->hasMany(RoomTransfer::class);
    }
}
