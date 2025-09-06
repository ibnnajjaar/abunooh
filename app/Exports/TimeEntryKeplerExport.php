<?php

namespace App\Exports;

use App\Models\TimeEntry;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMappedCells;

class TimeEntryKeplerExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    public function __construct(
        public Builder $query,
    )
    {
    }

    public function query(): Builder
    {
        $this->query->with('employee');
        return $this->query;
    }

    public function map($row): array
    {
        return [
            [
                $row->employee->system_identifier,
                $row->clock_in_time,
            ],
            [
                $row->employee->system_identifier,
                $row->clock_out_time,
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'EmpID',
            'Time'
        ];
    }
}
