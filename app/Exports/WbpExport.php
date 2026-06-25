<?php

namespace App\Exports;

use App\Enums\GenderType;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WbpExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    private int $row = 0;

    public function __construct(
        private Collection $data,
    ) {}

    public function collection(): Collection
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'No',
            'No. Registrasi',
            'Nama WBP',
            'Jenis Kelamin',
            'Pidana',
            'Blok - Kamar',
            'Tgl Masuk UPT',
            'Tgl Penempatan',
            'Tgl Ekspirasi',
            'Status',
        ];
    }

    public function map($inmate): array
    {
        $this->row++;

        return [
            $this->row,
            $inmate->registration_number,
            $inmate->name,
            $inmate->gender === GenderType::Male ? 'Laki-laki' : ($inmate->gender === GenderType::Female ? 'Perempuan' : '-'),
            $inmate->crime_type ?? '-',
            $inmate->currentRoom
                ? trim(($inmate->currentRoom->block?->name ?? '-').' - '.$inmate->currentRoom->name)
                : '-',
            $inmate->admission_date?->format('d/m/Y') ?? '-',
            $inmate->placement_date?->format('d/m/Y') ?? '-',
            $inmate->expiration_date?->format('d/m/Y') ?? '-',
            $inmate->status->label(),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
