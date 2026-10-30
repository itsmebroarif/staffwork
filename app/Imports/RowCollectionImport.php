<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RowCollectionImport implements ToCollection, WithHeadingRow
{
    /**
     * @var array<int, array<string, mixed>>
     */
    public array $rows = [];

    /**
     * @param  Collection<int, Collection<int, mixed>>  $rows
     */
    public function collection(Collection $rows): void
    {
        $this->rows = array_merge($this->rows, $rows
            ->map(fn (Collection $row): array => $row->toArray())
            ->reject(fn (array $row): bool => $row === [] || implode('', array_map(fn ($value) => (string) $value, $row)) === '')
            ->values()
            ->all());
    }
}
