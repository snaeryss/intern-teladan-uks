<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;

use App\Http\Requests\AcademicYear\StoreAcademicYearRequest;
use App\Http\Requests\AcademicYear\UpdateAcademicYearRequest;
use App\Models\AcademicYear;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AcademicYearController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        $title = 'Tahun Akademik';
        $academicYears = AcademicYear::orderBy('year_start', 'desc')->get();
        return view('academic-year.index', compact(
            'title',
            'academicYears',
        ));
    }

    /**
     * @param StoreAcademicYearRequest $request
     * @return RedirectResponse
     */
    public function store(
        StoreAcademicYearRequest $request
    ): RedirectResponse
    {
        $data = $request->validated();
        try {
            DB::transaction(static function () use ($data) {
                AcademicYear::firstOrCreate($data);
            });
            return back()->with('success', 'Berhasil Membuat Tahun Akademik');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return back()->with('error', 'Gagal Membuat Tahun Akademik');
        }
    }

    /**
     * @param UpdateAcademicYearRequest $request
     * @param AcademicYear $academicYear
     * @return RedirectResponse
     */
    public function update(
        UpdateAcademicYearRequest $request,
        AcademicYear              $academicYear,
    ): RedirectResponse
    {
        $data = $request->validated();
        try {
            DB::transaction(static function () use ($academicYear, $data) {
                $academicYear->update($data);
            });
            return back()->with('success', 'Berhasil Memperbaharui Tahun Akademik');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return back()->with('error', 'Gagal Memperbaharui Tahun Akademik');
        }
    }
}
