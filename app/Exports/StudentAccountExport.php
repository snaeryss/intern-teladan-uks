<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;

use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentAccountExport implements FromView, WithStyles, ShouldAutoSize
{
    private Collection $accounts;
    private string $className;

    /**
     * create and set data to export and export name
     * @param Collection $accounts
     * @param string $className
     */
    public function __construct(Collection $accounts, string $className)
    {
        $this->accounts = $accounts;
        $this->className = $className;
    }

    /**
     * inject data to html table templates
     * @return View
     */
    public function view(): View
    {
        $accounts = $this->accounts;
        $class_name = $this->className;
        return view(
            'students/export_accounts',
            compact('accounts', 'class_name')
        );
    }

    /**
     * set xlsx worksheet styles
     * @param Worksheet $sheet
     * @return void
     */
    public function styles(Worksheet $sheet): void
    {

        $sheet->getStyle('A')->getFont()->setBold(true);
        $sheet->getStyle('B1')->getFont()->setBold(true);
        $sheet->getStyle('C1')->getFont()->setBold(true);
        $sheet->getStyle('D1')->getFont()->setBold(true);
        $sheet->getStyle('D')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $allBorder = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $totalRow = count($this->accounts) + 2;
        try {
            $sheet->getStyle('A1:D' . $totalRow)
                ->applyFromArray($allBorder);
        } catch (Exception $e) {
            abort(500, $e->getMessage());
        }
    }
}
