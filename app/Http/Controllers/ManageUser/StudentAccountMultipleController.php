<?php

namespace App\Http\Controllers\ManageUser;

use App\Http\Controllers\Controller;

use App\Enums\AccountTypeEnum;
use App\Exports\StudentAccountExport;
use App\Models\Student;
use App\Models\StudentAccount;
use App\Models\User;
use App\Services\RandomStringService;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class StudentAccountMultipleController extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            "class_level" => "required",
            "class_name" => "required",
            "group_year" => "required|integer"
        ]);
        $students = Student::whereRelation(
            'studentClasses',
            static function ($query) use ($data) {
                $query->where('class_level', $data['class_level'])
                    ->where('class_name', $data['class_name'])
                    ->where("group_year", $data['group_year']);
            })
            ->orderBy('group_year')
            ->orderBy('nis')
            ->get();
        try {
            $new = 0;
            $skipped = 0;
            foreach ($students as $student) {
                $account = StudentAccount::where('student_id', $student->id)->first();
                if ($account) {
                    $skipped++;
                    continue;
                }
                DB::transaction(function () use ($student) {
                    $secret = RandomStringService::numericOnly(6);
                    $newUser['username'] = $student->nis;
                    $newUser['name'] = $student->name;
                    $newUser['password'] = bcrypt($secret);
                    $newUser['secret'] = Crypt::encrypt($secret);
                    $newUser['type'] = AccountTypeEnum::Student;
                    $user = User::firstOrCreate($newUser);
                    $newAccount['user_id'] = $user->id;
                    $newAccount['student_id'] = $student->id;
                    $account = StudentAccount::firstOrCreate($newAccount);
                    return $account->wasRecentlyCreated;
                });
                $new++;
            }
            return back()->with('success', $new . ' students have been created & ' . $skipped . ' students have been skipped.');
        } catch (Throwable $th) {
            Log::error($th->getMessage());
            return back()->with('error', 'Failed to create student account');
        }

    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function export(Request $request): BinaryFileResponse
    {
        $data = $request->validate([
            "class_level" => "required",
            "class_name" => "required",
            "group_year" => "required|integer"
        ]);
        $students = Student::whereRelation(
            'studentClasses',
            static function ($query) use ($data) {
                $query->where('class_level', $data['class_level'])
                    ->where('class_name', $data['class_name'])
                    ->where("group_year", $data['group_year']);
            })
            ->orderBy('group_year')
            ->orderBy('nis')
            ->get();
        foreach ($students as $student) {
            $user = $student->account->user;
            $student->decrypt_secret = '';
            if (isset($user->secret)) {
                $student->decrypt_secret = Crypt::decrypt($user->secret);
            }
        }
        $class_name = $data['class_level'] . $data['class_name'];
        return Excel::download(
            new StudentAccountExport($students, $class_name),
            'Student Account ' . $class_name . '.xlsx'
        );
    }
}