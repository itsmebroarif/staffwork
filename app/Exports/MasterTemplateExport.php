<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MasterTemplateExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @param  array<int, string>  $headings
     * @param  array<int, array<int, mixed>>  $rows
     */
    public function __construct(
        private array $headings,
        private array $rows,
    ) {}

    /**
     * @return Collection<int, array<int, mixed>>
     */
    public function collection(): Collection
    {
        return Collection::make($this->rows);
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return $this->headings;
    }

    /**
     * @param  array<int, mixed>  $row
     * @return array<int, mixed>
     */
    public function map($row): array
    {
        return array_values($row);
    }
}
