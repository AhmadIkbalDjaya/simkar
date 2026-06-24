<?php

namespace App\Models;

use App\Enums\GenderType;
use App\Enums\InmateStatus;
use Database\Factories\InmateFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'registration_number',
    'name',
    'gender',
    'crime_type',
    'admission_date',
    'placement_date',
    'expiration_date',
    'status',
    'current_room_id',
])]
class Inmate extends Model
{
    /** @use HasFactory<InmateFactory> */
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'gender' => GenderType::class,
            'admission_date' => 'date',
            'placement_date' => 'date',
            'expiration_date' => 'date',
            'status' => InmateStatus::class,
            'current_room_id' => 'integer',
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
