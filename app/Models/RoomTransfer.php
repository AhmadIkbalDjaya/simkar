<?php

namespace App\Models;

use App\Enums\TransferReasonType;
use App\Enums\TransferStatus;
use Database\Factories\RoomTransferFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'transfer_number',
    'inmate_id',
    'room_from_id',
    'room_to_id',
    'reason',
    'notes',
    'transferred_at',
    'status',
    'officer_name',
    'officer_signature',
    'created_by',
])]
class RoomTransfer extends Model
{
    /** @use HasFactory<RoomTransferFactory> */
    use HasFactory, SoftDeletes;

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $roomTransfer): void {
            if (empty($roomTransfer->transfer_number)) {
                do {
                    $number = 'TRF-'.str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
                } while (self::where('transfer_number', $number)->exists());

                $roomTransfer->transfer_number = $number;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'reason' => TransferReasonType::class,
            'transferred_at' => 'datetime',
            'status' => TransferStatus::class,
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
