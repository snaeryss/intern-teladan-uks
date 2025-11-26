<?php

namespace App\Http\Controllers\MedicalRecord;

use App\Exports\StudentReportExport;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Period;
use App\Models\Mcu;
use App\Models\Dcu;
use App\Models\StudentClass;
use App\Models\Student;
use App\Enums\CheckUpTypeEnum;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    public function index(): View
    {
        $title = 'List Tahun Akademik';

        // Ambil data tahun akademik dengan jumlah kegiatan (periods)
        $reports = AcademicYear::withCount('periods')
            ->orderBy('year_start', 'desc')
            ->get();

        return view('reports.index', compact('title', 'reports'));
    }

    public function show($id): View
    {
        $academicYear = AcademicYear::findOrFail($id);
        $title = 'Detail Kegiatan';

        // Ambil semua periode berdasarkan tahun akademik dengan jumlah peserta
        $activities = Period::where('academic_year_id', $id)
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function ($period) {
                // Hitung peserta berdasarkan type periode
                $participants = 0;
                $checkupType = $this->getCheckupType($period->name);

                // Hitung peserta sesuai tipe kegiatan
                if ($checkupType === CheckUpTypeEnum::DentalCheckUp) {
                    $participants = Dcu::where('period_id', $period->id)->count();
                } elseif (in_array($checkupType, [CheckUpTypeEnum::Screening, CheckUpTypeEnum::MedicalCheckUp])) {
                    $participants = Mcu::where('period_id', $period->id)->count();
                }

                return (object)[
                    'id' => $period->id,
                    'name' => $period->name, // Tampilkan nama asli dari period
                    'periode' => $period->month . ' ' . $period->year,
                    'participants' => $participants,
                    'original_name' => $period->name, // untuk routing
                ];
            });

        return view('reports.detail', compact('title', 'academicYear', 'activities'));
    }

    public function showActivityDetail($id, $activity): View
    {
        $academicYear = AcademicYear::findOrFail($id);

        // Tentukan title berdasarkan jenis kegiatan
        $checkupType = $this->getCheckupType($activity);
        $title = $this->getActivityTitle($checkupType);

        // Ambil periode berdasarkan activity name
        $periods = Period::where('academic_year_id', $id)
            ->where(function ($query) use ($activity) {
                $query->where('name', 'LIKE', '%' . $activity . '%');
            })
            ->pluck('id');

        if ($periods->isEmpty()) {
            return view('reports.activity_detail', compact('title', 'academicYear', 'activity'))
                ->with('classes', collect());
        }

        $groupYear = substr($academicYear->year_start, -2);

        // Ambil student_id yang terdaftar di kegiatan periode ini berdasarkan type
        if ($checkupType === CheckUpTypeEnum::DentalCheckUp) {
            $registeredStudentIds = Dcu::whereIn('period_id', $periods)
                ->pluck('student_id')
                ->unique();
        } else {
            $registeredStudentIds = Mcu::whereIn('period_id', $periods)
                ->pluck('student_id')
                ->unique();
        }

        // Jika tidak ada siswa terdaftar, return empty
        if ($registeredStudentIds->isEmpty()) {
            return view('reports.activity_detail', compact('title', 'academicYear', 'activity'))
                ->with('classes', collect());
        }

        // Ambil kelas yang memiliki siswa terdaftar di kegiatan ini
        $classes = StudentClass::select(
            'student_classes.class_name',
            DB::raw('MIN(student_classes.id) as id'),
            DB::raw('COUNT(DISTINCT student_classes.student_id) as total_students')
        )
            ->where('student_classes.group_year', $groupYear)
            ->where('student_classes.school_level', '!=', '')
            ->whereIn('student_classes.student_id', $registeredStudentIds)
            ->join('students', 'students.id', '=', 'student_classes.student_id')
            ->where('students.status', true)
            ->groupBy('student_classes.class_name')
            ->orderByRaw('CAST(SUBSTRING_INDEX(student_classes.class_name, " ", 1) AS UNSIGNED)') // Sort by class number
            ->orderBy('student_classes.class_name')
            ->get()
            ->map(function ($class) use ($periods, $checkupType, $registeredStudentIds) {
                // Ambil siswa di kelas ini
                $studentIdsInClass = StudentClass::where('class_name', $class->class_name)
                    ->whereIn('student_id', $registeredStudentIds)
                    ->pluck('student_id');

                // Hitung siswa yang sudah terdaftar di kegiatan ini
                if ($checkupType === CheckUpTypeEnum::DentalCheckUp) {
                    $completedCount = Dcu::whereIn('period_id', $periods)
                        ->whereIn('student_id', $studentIdsInClass)
                        ->distinct('student_id')
                        ->count('student_id');
                } else {
                    $completedCount = Mcu::whereIn('period_id', $periods)
                        ->whereIn('student_id', $studentIdsInClass)
                        ->distinct('student_id')
                        ->count('student_id');
                }

                return (object)[
                    'id' => $class->id,
                    'name' => $class->class_name,
                    'student_count' => $class->total_students,
                    'completed_count' => $completedCount,
                ];
            });

        return view('reports.activity_detail', compact('title', 'academicYear', 'activity', 'classes'));
    }

    public function showStudentList($id, $activity, $class_id): View
    {
        $academicYear = AcademicYear::findOrFail($id);

        // Tentukan title berdasarkan jenis kegiatan
        $checkupType = $this->getCheckupType($activity);
        $title = 'Daftar Riwayat ' . ($checkupType ? $checkupType->label() : Str::upper($activity));

        // Ambil periode berdasarkan activity name
        $periods = Period::where('academic_year_id', $id)
            ->where(function ($query) use ($activity) {
                $query->where('name', 'LIKE', '%' . $activity . '%');
            })
            ->get();

        if ($periods->isEmpty()) {
            return view('reports.student_list', compact('title', 'academicYear', 'activity', 'class_id'))
                ->with('students', collect());
        }

        // Ambil nama kelas dari student_class
        $className = StudentClass::find($class_id)->class_name ?? '';
        $groupYear = substr($academicYear->year_start, -2);

        // Ambil student_id yang TERDAFTAR di kegiatan periode ini
        if ($checkupType === CheckUpTypeEnum::DentalCheckUp) {
            $registeredStudentIds = Dcu::whereIn('period_id', $periods->pluck('id'))
                ->pluck('student_id')
                ->unique();
        } else {
            $registeredStudentIds = Mcu::whereIn('period_id', $periods->pluck('id'))
                ->pluck('student_id')
                ->unique();
        }

        // Ambil siswa di kelas ini yang JUGA terdaftar di kegiatan
        $studentIdsInClass = StudentClass::where('class_name', $className)
            ->where('group_year', $groupYear)
            ->whereIn('student_id', $registeredStudentIds) // FILTER: hanya yang terdaftar di kegiatan
            ->pluck('student_id');

        // Jika tidak ada siswa terdaftar di kelas ini
        if ($studentIdsInClass->isEmpty()) {
            return view('reports.student_list', compact('title', 'academicYear', 'activity', 'class_id'))
                ->with('students', collect());
        }

        $students = Student::whereIn('id', $studentIdsInClass)
            ->where('status', true)
            ->orderBy('name')
            ->get()
            ->map(function ($student) use ($periods, $checkupType) {
                // Ambil data checkup siswa (pasti ada karena sudah difilter)
                $checkup = null;
                $schedule = '-';
                $status = 'Belum Selesai';
                $periodId = null;

                // Ambil checkup berdasarkan type
                if ($checkupType === CheckUpTypeEnum::DentalCheckUp) {
                    $checkup = Dcu::whereIn('period_id', $periods->pluck('id'))
                        ->where('student_id', $student->id)
                        ->with('period')
                        ->first();
                } else {
                    $checkup = Mcu::whereIn('period_id', $periods->pluck('id'))
                        ->where('student_id', $student->id)
                        ->with('period')
                        ->first();
                }

                if ($checkup) {
                    $schedule = $checkup->period->month . ' ' . $checkup->period->year;
                    $status = $checkup->is_finish ? 'Selesai' : 'Belum Selesai';
                    $periodId = $checkup->period_id;
                }

                // Tentukan level sekolah untuk routing
                $level = match ($student->school_level->value ?? '') {
                    'SD' => 'sd',
                    'SMP' => 'smp',
                    'SMA' => 'sma',
                    default => 'kb-tk'
                };

                return (object)[
                    'id' => $student->id,
                    'name' => $student->name,
                    'nis' => $student->nis,
                    'schedule' => $schedule,
                    'status' => $status,
                    'level' => $level,
                    'checkup_id' => $checkup ? $checkup->id : null,
                    'period_id' => $periodId,
                ];
            });

        return view('reports.student_list', compact(
            'title',
            'academicYear',
            'activity',
            'class_id',
            'students'
        ));
    }

    public function exportStudentList($id, $activity, $class_id): BinaryFileResponse
    {
        $academicYear = AcademicYear::findOrFail($id);

        // Tentukan checkup type
        $checkupType = $this->getCheckupType($activity);

        // Ambil periode berdasarkan activity name
        $periods = Period::where('academic_year_id', $id)
            ->where(function ($query) use ($activity) {
                $query->where('name', 'LIKE', '%' . $activity . '%');
            })
            ->get();

        $className = StudentClass::find($class_id)->class_name ?? 'Unknown';
        $groupYear = substr($academicYear->year_start, -2);

        // FILTER 1: Ambil student_id yang TERDAFTAR di kegiatan periode ini
        if ($checkupType === CheckUpTypeEnum::DentalCheckUp) {
            $registeredStudentIds = Dcu::whereIn('period_id', $periods->pluck('id'))
                ->pluck('student_id')
                ->unique();
        } else {
            $registeredStudentIds = Mcu::whereIn('period_id', $periods->pluck('id'))
                ->pluck('student_id')
                ->unique();
        }

        // FILTER 2: Ambil siswa di kelas ini yang JUGA terdaftar di kegiatan
        $studentIdsInClass = StudentClass::where('class_name', $className)
            ->where('group_year', $groupYear)
            ->whereIn('student_id', $registeredStudentIds) // FILTER: hanya yang terdaftar di kegiatan
            ->pluck('student_id');

        // Query siswa yang sudah difilter
        $students = Student::whereIn('id', $studentIdsInClass)
            ->where('status', true)
            ->orderBy('name')
            ->get()
            ->map(function ($student) use ($periods, $checkupType) {
                $checkup = null;
                $schedule = '-';
                $status = 'Belum Selesai';

                // Ambil checkup berdasarkan type
                if ($checkupType === CheckUpTypeEnum::DentalCheckUp) {
                    $checkup = Dcu::whereIn('period_id', $periods->pluck('id'))
                        ->where('student_id', $student->id)
                        ->with('period')
                        ->first();
                } else {
                    $checkup = Mcu::whereIn('period_id', $periods->pluck('id'))
                        ->where('student_id', $student->id)
                        ->with('period')
                        ->first();
                }

                if ($checkup) {
                    $schedule = $checkup->period->month . ' ' . $checkup->period->year;
                    $status = $checkup->is_finish ? 'Selesai' : 'Belum Selesai';
                }

                return (object)[
                    'name' => $student->name,
                    'nis' => $student->nis,
                    'schedule' => $schedule,
                    'status' => $status,
                ];
            });

        // Nama activity untuk filename
        $activityName = $checkupType ? $checkupType->value : Str::upper($activity);

        // Format nama kelas untuk filename (ganti spasi dengan underscore)
        $classNameForFile = str_replace(' ', '_', $className);

        // Format: Laporan_DCU_Kelas_1_A_2025-10-12.xlsx
        $fileName = 'Laporan_' . $activityName . '_Kelas_' . $classNameForFile . '_' . date('Y-m-d') . '.xlsx';

        return Excel::download(
            new StudentReportExport($students, $className, $activityName),
            $fileName
        );
    }

    /**
     * Helper method untuk mendapatkan checkup type dari nama kegiatan
     */
    private function getCheckupType(string $activityName): ?CheckUpTypeEnum
    {
        $activityUpper = Str::upper($activityName);

        // Cek berdasarkan enum values
        if (Str::contains($activityUpper, ['DCU', 'DENTAL', 'GIGI'])) {
            return CheckUpTypeEnum::DentalCheckUp;
        }

        if (Str::contains($activityUpper, ['MCU', 'MEDICAL'])) {
            return CheckUpTypeEnum::MedicalCheckUp;
        }

        if (Str::contains($activityUpper, ['SCR', 'SCREENING'])) {
            return CheckUpTypeEnum::Screening;
        }

        return null;
    }

    /**
     * Helper method untuk mendapatkan title berdasarkan checkup type
     */
    private function getActivityTitle(?CheckUpTypeEnum $checkupType): string
    {
        if ($checkupType === CheckUpTypeEnum::DentalCheckUp) {
            return 'Laporan Kegiatan DCU';
        }

        if ($checkupType === CheckUpTypeEnum::MedicalCheckUp) {
            return 'Laporan Kegiatan MCU';
        }

        if ($checkupType === CheckUpTypeEnum::Screening) {
            return 'Laporan Kegiatan Screening';
        }

        return 'Laporan Kegiatan';
    }
}
