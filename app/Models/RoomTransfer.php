<?php

namespace App\Models;

use Database\Factories\RoomTransferFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'inmate_id',
    'room_from_id',
    'room_to_id',
    'transferred_at',
    'officer_name',
    'officer_signature',
    'notes',
    'created_by',
])]
class RoomTransfer extends Model
{
    /** @use HasFactory<RoomTransferFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'transferred_at' => 'datetime',
        ];
    }

    public function inmate(): BelongsTo
    {
        return $this->belongsTo(Inmate::class);
    }

    public function roomFrom(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_from_id');
    }

    public function roomTo(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_to_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
