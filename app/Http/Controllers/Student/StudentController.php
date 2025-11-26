<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Models\Student;
use App\Models\StudentAccount;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function index(): View
    {
        $title = 'Students';
        $students = Student::orderBy('group_year')
            ->orderBy('nis')
            ->get();

        return view('students.index', compact(
            'title',
            'students',
        ));
    }

    public function show(Student $student): View
    {
        $title = 'Student Detail';
        $account = StudentAccount::where('student_id', $student->id)->first();
        return view('students.detail', compact(
            'title',
            'student',
            'account',
        ));
    }

    public function update(
        UpdateStudentRequest $request,
        Student              $student,
    ): RedirectResponse
    {
        $data = $request->validated();
        try {
            DB::transaction(static function () use ($student, $data) {
                $student->update($data);
            });
            return back()->with('success', 'Berhasil Memperbaharui Data Siswa');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return back()->with('error', 'Gagal Memperbaharui Data Siswa');
        }
    }
}
