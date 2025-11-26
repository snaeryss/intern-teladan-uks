<?php

namespace App\Http\Controllers\ManageUser;

use App\Http\Controllers\Controller;

use App\Enums\AccountTypeEnum;
use App\Models\Student;
use App\Models\StudentAccount;
use App\Models\User;
use App\Services\RandomStringService;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class StudentAccountController extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'student' => ['required', 'exists:students,id'],
        ]);
        $studentId = $data['student'];
        $secret = RandomStringService::numericOnly(6);
        $account = StudentAccount::where('student_id', $studentId)->first();
        if (!$account) {
            try {
                DB::transaction(function () use ($studentId, $secret) {
                    $student = Student::find($studentId);
                    $encryptSecret = Crypt::encrypt($secret);
                    $data['username'] = $student->nis;
                    $data['name'] = $student->name;
                    $data['password'] = bcrypt($secret);
                    $data['secret'] = $encryptSecret;
                    $data['type'] = AccountTypeEnum::Student;
                    $user = User::firstOrCreate($data);
                    $newAccount['user_id'] = $user->id;
                    $newAccount['student_id'] = $studentId;
                    $account = StudentAccount::firstOrCreate($newAccount);
                    return $account->wasRecentlyCreated;
                });
                return back()->with('success', 'Student account has been created');
            } catch (Throwable $th) {
                Log::error($th->getMessage());
                return back()->with('error', 'Failed to create student account');
            }
        }
        return back()->with('success', 'Student account already exists');
    }

    /**
     * @param Student $student
     * @return JsonResponse
     */
    public function show(Student $student): JsonResponse
    {
        $account = StudentAccount::where('student_id', $student->id)->first();
        if ($account) {
            $user = User::find($account->user_id);
            $user->uncrypted = Crypt::decrypt($user->secret);
            return response()->json(
                [
                    'data' => $user,
                ],
            );
        } else {
            return response()->json(
                [
                    'message' => 'Bad Request',
                ],
                400
            );
        }
    }
}