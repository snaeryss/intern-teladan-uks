<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;

use App\Models\Student;
use App\Models\StudentSyncHistory;
use App\Services\AcademicService;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SyncStudentsController extends Controller
{
	/**
	 * @return View
	 */
	public function index(): View
	{
        $title = 'Sinkronasi Kelas';
		$histories = StudentSyncHistory::where('sync_type', 0)
			->orderBy('created_at', 'desc')
			->get();
		return view('sync.students', compact('title','histories'));
	}

	/**
	 * synchronize student data new, skip or update
	 * @param Request $request
	 * @return RedirectResponse
	 */
	public function store(Request $request): RedirectResponse
	{
		$request->validate(['admin' => 'required|integer|exists:users,id']);
		$dataFromAPI = AcademicService::getFromAcademic(AcademicService::STUDENTS);
		if ($dataFromAPI->code == AcademicService::FAIL_CONNECTION || $dataFromAPI->code == AcademicService::FAIL_REQUEST) {
			return back()->with(['error' => $dataFromAPI->message]);
		}

		if (!isset($dataFromAPI->data['msg']) || !is_array($dataFromAPI->data['msg'])) {
			return back()->with(['error' => 'Data siswa tidak ditemukan dalam response API']);
		}
		
		$students = $dataFromAPI->data['msg'];
		$history = DB::transaction(static function () use ($students) {
			$new = 0;
			$skipped = 0;
			$updated = 0;
			foreach ($students as $student) {
				$oldStudent = Student::find($student['siswa_id']);
				if ($oldStudent) {
					if ($oldStudent->name != $student['siswa_nama_full']
						|| $oldStudent->date_birth != Carbon::parse($student['siswa_tgl_lhr'])
						|| $oldStudent->sex != $student['siswa_kelamin']
						|| $oldStudent->nis != $student['siswa_nis']
						|| $oldStudent->group_year != $student['siswa_angkatan']
					) {
						$oldStudent->name = $student['siswa_nama_full'];
						$oldStudent->date_birth = $student['siswa_tgl_lhr'];
						$oldStudent->sex = $student['siswa_kelamin'];
						$oldStudent->nis = $student['siswa_nis'];
						$oldStudent->group_year = $student['siswa_angkatan'];
						$oldStudent->save();
						$updated++;
					} else {
						$skipped++;
					}
				} else {
					Student::firstOrCreate([
						'id' => $student['siswa_id'],
						'name' => $student['siswa_nama_full'],
						'nis' => $student['siswa_nis'],
						'date_birth' => $student['siswa_tgl_lhr'],
						'sex' => $student['siswa_kelamin'],
						'school_level' => $student['jenjang'],
						'group_year' => $student['siswa_angkatan'],
						'status' => 1
					]);
					$new++;
				}
			}
			return StudentSyncHistory::create([
				'user_id' => Auth::user()->getAuthIdentifier(),
				'new' => $new,
				'skipped' => $skipped,
				'updated' => $updated,
				'sync_type' => 0
			]);
		});
		return back()->with(
			[
				'success' => 'Berhasil menambahkan '
					. $history->new . ' Siswa Baru dan memperbaharui '
					. $history->updated . ' Siswa'
			]
		);
	}
}
