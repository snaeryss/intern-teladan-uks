<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentLiveSearchApiController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $search = $request->input('term');
        $level = $request->input('level');
        $page = $request->input('page', 1);

        if (empty($search) || empty($level)) {
            return response()->json([
                'results' => [],
                'pagination' => ['more' => false]
            ]);
        }

        if ($level === 'all') {
            $students = Student::where('status', true) // Filter siswa aktif
                ->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%")
                        ->orWhere('nis', 'LIKE', "%$search%");
                })
                ->select('id', 'name', 'nis')
                ->paginate(10, ['*'], 'page', $page)
                ->through(function ($student) {
                    return [
                        'id' => $student->id,
                        'text' => "{$student->nis} - {$student->name}",
                    ];
                });

            return response()->json([
                'results' => $students->items(),
                'pagination' => [
                    'more' => $students->hasMorePages()
                ]
            ]);
        }

        $levelEnum = match ($level) {
            'dctk' => [\App\Enums\Student\Level::DC, \App\Enums\Student\Level::KB, \App\Enums\Student\Level::TK],
            'sd' => [\App\Enums\Student\Level::SD],
            'smp' => [\App\Enums\Student\Level::SMP],
            'sma' => [\App\Enums\Student\Level::SMA],
            default => [],
        };

        $students = Student::whereIn('school_level', $levelEnum)
            ->where('status', true) // Filter siswa aktif
            ->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%")
                    ->orWhere('nis', 'LIKE', "%$search%");
            })
            ->select('id', 'name', 'nis')
            ->paginate(10, ['*'], 'page', $page)
            ->through(function ($student) {
                return [
                    'id' => $student->id,
                    'text' => "{$student->nis} - {$student->name}"
                ];
            });

        return response()->json([
            'results' => $students->items(),
            'pagination' => [
                'more' => $students->hasMorePages()
            ]
        ]);
    }
}
