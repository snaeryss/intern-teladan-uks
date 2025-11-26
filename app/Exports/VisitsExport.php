<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class VisitsExport implements FromCollection, WithHeadings, WithColumnWidths, WithEvents
{
    protected $visits;

    public function __construct($visits)
    {
        $this->visits = $visits;
    }

    public function collection()
    {
        return $this->visits->map(function ($visit, $index) {
            return [
                'no' => $index + 1,
                'hari_tanggal' => $this->formatHariTanggal($visit->date, $visit->day),
                'nama_siswa' => $visit->student->name ?? '-',
                'kelas' => $this->formatKelas($visit),
                'waktu_masuk' => $visit->arrival_time ?? '-',
                'waktu_keluar' => $visit->departure_time ?? '-',
                'keluhan' => $visit->complaint ?? '-',
                'penanganan' => $visit->treatment ?? '-',
                'hasil_keterangan' => $visit->outcome_notes ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            [
                'No',
                'Hari, Tanggal',
                'Nama Siswa',
                'Kelas',
                'Waktu',
                '',
                'Keluhan',
                'Penanganan',
                'Hasil/Keterangan',
            ],
            [
                '',
                '',
                '',
                '',
                'Masuk',
                'Keluar',
                '',
                '',
                ''
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 25,
            'C' => 25,
            'D' => 12,
            'E' => 12,
            'F' => 12,
            'G' => 30,
            'H' => 35,
            'I' => 35,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                $sheet->mergeCells('A1:A2');
                $sheet->mergeCells('B1:B2');
                $sheet->mergeCells('C1:C2');
                $sheet->mergeCells('D1:D2');
                $sheet->mergeCells('E1:F1');
                $sheet->mergeCells('G1:G2');
                $sheet->mergeCells('H1:H2');
                $sheet->mergeCells('I1:I2');

                $sheet->getStyle('A1:I2')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                $lastRow = $this->visits->count() + 2;
                $sheet->getStyle("A3:I{$lastRow}")->applyFromArray([
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_TOP,
                        'wrapText' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                $sheet->getStyle("A3:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("E3:F{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
        ];
    }

    private function formatHariTanggal($date, $day = null)
    {
        $carbonDate = \Carbon\Carbon::parse($date);
        $hari = $day ?? $carbonDate->translatedFormat('l');
        $tanggal = $carbonDate->translatedFormat('d F Y');
        return "{$hari}, {$tanggal}";
    }

    private function formatKelas($visit)
{
    if (!$visit->student) {
        return '-';
    }

    $levelName = match ($visit->student->school_level->value ?? null) {
        '00' => 'DCKBTK',  
        '11' => 'DCKBTK',  
        '22' => 'DCKBTK',  
        '33' => 'SD',      
        '44' => 'SMP',    
        '55' => 'SMA',     
        default => ''
    };

    $currentClass = $visit->student->studentClasses
        ->sortByDesc('group_year')
        ->first();

    $className = '';
    if ($currentClass) {
        $className = $currentClass->class_name; 
    }

    if ($levelName && $className) {
        return "{$levelName} {$className}";
    } elseif ($levelName) {
        return $levelName;
    }

    return '-';
}
}
