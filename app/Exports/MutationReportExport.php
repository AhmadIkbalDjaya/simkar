<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MutationReportExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
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
            'Nama WBP',
            'Kamar Asal',
            'Kamar Tujuan',
            'Waktu Mutasi',
            'Petugas',
            'Catatan',
        ];
    }

    public function map($transfer): array
    {
        $this->row++;

        return [
            $this->row,
            $transfer->inmate->name,
            $transfer->roomFrom->name,
            $transfer->roomTo->name,
            $transfer->transferred_at->format('d/m/Y H:i'),
            $transfer->officer_name,
            $transfer->notes ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
