<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;

use App\Models\Student;
use App\Models\StudentClass;
use App\Models\StudentSyncHistory;
use App\Services\AcademicService;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SyncClassesController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        $title = 'Sinkronasi Kelas';
        $histories = StudentSyncHistory::where('sync_type', 1)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('sync.classes', compact('title','histories'));
    }

    /**
     * synchronize student class data new, skip or update
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(['admin' => 'required|integer|exists:users,id']);
        $dataFromAPI = AcademicService::getFromAcademic(AcademicService::CLASSES);
        if ($dataFromAPI->code == AcademicService::FAIL_CONNECTION || $dataFromAPI->code == AcademicService::FAIL_REQUEST) {
            return back()->with(['error' => $dataFromAPI->message]);
        }

        if (!isset($dataFromAPI->data['msg']) || !is_array($dataFromAPI->data['msg'])) {
            return back()->with(['error' => 'Data kelas tidak ditemukan dalam response API']);
        }
        
        $studentClasses = $dataFromAPI->data['msg'];
        $history = DB::transaction(static function () use ($studentClasses) {
            $new = 0;
            $skipped = 0;
            $updated = 0;
            foreach ($studentClasses as $student) {
                $studentExist = Student::find($student['siswa_id']);
                if ($studentExist) {
                    $oldClass = StudentClass::where([
                        'student_id' => $student['siswa_id'],
                        'group_year' => $student['ta_mulai']
                    ])->first();
                    if ($oldClass) {
                        if ($oldClass->class_name != $student['rombel_nama']) {
                            $oldClass->class_name = $student['rombel_nama'];
                            $oldClass->save();
                            $updated++;
                        } else {
                            $skipped++;
                        }
                    } else {
                        StudentClass::firstOrCreate([
                            'student_id' => $student['siswa_id'],
                            'class_level' => $student['kelas_tingkat_nama'],
                            'class_name' => $student['rombel_nama'],
                            'group_year' => $student['ta_mulai'],
                            'school_level' => $student['kelas_jenjang'],
                        ]);
                        $new++;
                    }
                } else {
                    $skipped++;
                }
            }
            return StudentSyncHistory::create([
                'user_id' => Auth::user()->getAuthIdentifier(),
                'new' => $new,
                'skipped' => $skipped,
                'updated' => $updated,
                'sync_type' => 1
            ]);
        });
        return back()->with(
            [
                'success' => 'Berhasil menambahkan '
                    . $history->new . ' Kelas Baru dan memperbaharui '
                    . $history->updated . ' Kelas'
            ]
        );
    }
}
