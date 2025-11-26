<?php

namespace App\Http\Controllers\Print;

use App\Http\Controllers\Controller;
use App\Models\Mcu;
use App\Models\Dcu;
use App\Models\Student;
use App\Models\Visit;
use App\Repositories\StudentRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\VisitsExport;
use Maatwebsite\Excel\Facades\Excel;

class PrintDocumentController extends Controller
{
    public function mcu(): View
    {
        $title = 'Cetak Dokumen MCU';
        $levels = StudentRepository::getGroupedLevels();
        return view('print.mcu.index', compact('title', 'levels'));
    }

    public function showMcu(Request $request): View
    {
        $title = 'Hasil Cetak Dokumen MCU';

        $request->validate([
            'student_id' => 'required',
            'level' => 'required'
        ]);

        $records = Mcu::with(['period', 'student'])
            ->where('student_id', $request->student_id)
            ->where('is_finish', true)
            ->get();

        $selectedStudent = Student::find($request->student_id);
        $selectedLevel = StudentRepository::getLevelNameByCode($request->level);

        return view('print.mcu.show', compact('title', 'records', 'selectedStudent', 'selectedLevel'));
    }

    public function dcu(): View
    {
        $title = 'Cetak Dokumen DCU';
        $levels = StudentRepository::getGroupedLevels();
        return view('print.dcu.index', compact('title', 'levels'));
    }

    public function showDcu(Request $request): View
    {
        $title = 'Hasil Cetak Dokumen DCU';

        $request->validate([
            'student_id' => 'required',
            'level' => 'required'
        ]);

        $records = Dcu::with(['period', 'student'])
            ->where('student_id', $request->student_id)
            ->where('is_finish', true)
            ->get();

        $selectedStudent = Student::find($request->student_id);
        $selectedLevel = StudentRepository::getLevelNameByCode($request->level);

        return view('print.dcu.show', compact('title', 'records', 'selectedStudent', 'selectedLevel'));
    }

    public function visits(Request $request): View
    {
        $title = 'Cetak Dokumen Kunjungan';

        $monthlyVisits = Visit::selectRaw('
            YEAR(date) as year,
            MONTH(date) as month,
            COUNT(*) as total
        ')
            ->whereNotNull('date')
            ->groupByRaw('YEAR(date), MONTH(date)')
            ->orderByRaw('YEAR(date) DESC, MONTH(date) DESC')
            ->get()
            ->map(function ($visit) {
                $visit->date = \Carbon\Carbon::create($visit->year, $visit->month, 1);
                return $visit;
            });

        return view('print.visits.index', compact('title', 'monthlyVisits'));
    }

    public function showVisits(Request $request): View
    {
        $month = $request->query('month');
        $year = $request->query('year');

        $title = 'Detail Laporan Kunjungan';

        $visits = Visit::with(['student'])
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'ASC')
            ->orderBy('arrival_time', 'ASC')
            ->get();

        $periodeName = \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y');

        return view('print.visits.show', compact('title', 'visits', 'periodeName', 'month', 'year'));
    }

    public function exportVisits(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year');

        $visits = Visit::with(['student.studentClasses'])
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'ASC')
            ->orderBy('arrival_time', 'ASC')
            ->get();

        $periodeName = \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y');

        $filename = 'Laporan_Kunjungan_' . str_replace(' ', '_', $periodeName) . '.xlsx';

        return Excel::download(new VisitsExport($visits, $periodeName), $filename);
    }
}
