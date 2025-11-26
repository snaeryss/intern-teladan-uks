<?php

namespace App\Http\Controllers;

use App\Enums\CheckUpTypeEnum;
use App\Enums\MonthEnum;
use App\Models\AcademicYear;
use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PeriodController extends Controller
{
    public function show($academicYearId)
    {
        $academicYear = AcademicYear::findOrFail($academicYearId);
 
        $periods = Period::where('academic_year_id', $academicYearId)
            ->orderBy('year', 'desc')
            ->get()
            ->sortByDesc(function ($period) {
                $monthEnum = MonthEnum::from($period->month);
                return $period->year * 100 + $monthEnum->order();
            });

        $checkupTypes = CheckUpTypeEnum::cases();
        $months = MonthEnum::cases();

        $title = 'Periode Kegiatan';

        return view('periods.show', compact('academicYear', 'periods', 'checkupTypes', 'months', 'title'));
    }

    
    public function store(Request $request, $academicYearId)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', Rule::in(['SCR', 'MCU', 'DCU'])],
            'month' => ['required', 'string', Rule::in(MonthEnum::values())],
            'year' => 'required|integer|min:2020|max:2100',
        ]);

        $academicYear = AcademicYear::findOrFail($academicYearId);

        $exists = Period::where('academic_year_id', $academicYearId)
            ->where('name', $validated['name'])
            ->where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Periode dengan kegiatan, bulan, dan tahun yang sama sudah ada!');
        }

        Period::create([
            'academic_year_id' => $academicYearId,
            'name' => $validated['name'],
            'month' => $validated['month'],
            'year' => $validated['year'],
            'is_active' => false, 
        ]);

        return redirect()->back()->with('success', 'Periode berhasil ditambahkan!');
    }

    public function update(Request $request, $periodId)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', Rule::in(['SCR', 'MCU', 'DCU'])],
            'month' => ['required', 'string', Rule::in(MonthEnum::values())],
            'year' => 'required|integer|min:2020|max:2100',
        ]);

        $period = Period::findOrFail($periodId);

        $exists = Period::where('academic_year_id', $period->academic_year_id)
            ->where('name', $validated['name'])
            ->where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->where('id', '!=', $periodId)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Periode dengan kegiatan, bulan, dan tahun yang sama sudah ada!');
        }

        $period->update($validated);

        return redirect()->back()->with('success', 'Periode berhasil diperbarui!');
    }

    public function toggleStatus($periodId)
    {
        $period = Period::findOrFail($periodId);
        $period->is_active = !$period->is_active;
        $period->save();

        $status = $period->is_active ? 'aktif' : 'nonaktif';
        return redirect()->back()->with('success', "Status periode berhasil diubah menjadi {$status}!");
    }
}