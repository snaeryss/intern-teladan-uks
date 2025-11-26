<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $students;
    protected $className;
    protected $activityName;

    public function __construct(Collection|array $students, string $className = '', string $activityName = '')
    {
        $this->students = $students instanceof Collection ? $students : collect($students);
        $this->className = $className;
        $this->activityName = $activityName;
    }

    public function collection(): Collection
    {
        return $this->students instanceof Collection ? $this->students : collect($this->students);
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'NIS',
            'Jadwal ' . $this->activityName,
            'Status',
        ];
    }
    
    public function map($row): array
    {
        return [
            $row->name,
            $row->nis,
            $row->schedule,
            $row->status,
        ];
    }
}