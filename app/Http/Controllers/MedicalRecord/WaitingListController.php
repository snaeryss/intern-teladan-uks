<?php

namespace App\Http\Controllers\MedicalRecord;

use App\Http\Controllers\Controller;

use App\Enums\Student\Level;
use App\Models\Dcu;
use App\Models\Location;
use App\Models\Mcu;
use App\Models\Period;
use App\Models\Student;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WaitingListController extends Controller
{
 
    public function index(): View
    {
        $title = "Pilih Jenjang Pendaftaran";
        return view('waiting-list.index', compact('title'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|uuid|exists:students,id',
            'period_id' => 'required|integer|exists:periods,id',
            'location_id' => 'required|integer|exists:locations,id',
            'date' => 'required|date',
        ]);

        $period = Period::find($validated['period_id']);

        if (!$period) {
            return response()->json(['error' => 'Data kegiatan tidak ditemukan.'], 404);
        }

        $isAlreadyRegistered = false;
        $redirectUrl = '';

        $activityType = strtoupper($period->name);
        
        $isPerawatUks = auth()->user()->hasRole('Perawat UKS');

        if ($activityType == 'DCU') {
            if (Dcu::where('student_id', $validated['student_id'])->where('period_id', $validated['period_id'])->exists()) {
                $isAlreadyRegistered = true;
            }

            $dcu = Dcu::firstOrCreate(
                [
                    'student_id' => $validated['student_id'],
                    'period_id' => $validated['period_id']
                ],
                [
                    'code' => 'DCU-' . Str::random(8),
                    'date' => $validated['date'],              
                    'location_id' => $validated['location_id'], 
                ]
            );
            
            if ($isPerawatUks) {
                $redirectUrl = route('waiting-list.index');
            } else {
                $redirectUrl = route('dcu.form', [
                    'student' => $dcu->student_id,
                    'period' => $dcu->period_id
                ]);
            }

        } elseif ($activityType == 'MCU' || $activityType == 'SCR') {
            if (Mcu::where('student_id', $validated['student_id'])->where('period_id', $validated['period_id'])->exists()) {
                $isAlreadyRegistered = true;
            }

            $mcu = Mcu::firstOrCreate(
                [
                    'student_id' => $validated['student_id'],
                    'period_id' => $validated['period_id']
                ],
                [
                    'code' => 'MCU-' . Str::random(8),
                    'date' => $validated['date'],              
                    'location_id' => $validated['location_id'], 
                ]
            );
            $redirectUrl = route('medical-checkup.form', [
                'student' => $mcu->student_id,
                'period' => $mcu->period_id
            ]);
        } else {
            return response()->json(['error' => 'Jenis kegiatan tidak dikenali: ' . $activityType], 422);
        }
        
        return response()->json([
            'redirect_url' => $redirectUrl,
            'is_registered' => $isAlreadyRegistered
        ]);
    }

    public function show(string $level): View
    {
        $title = 'Formulir Daftar Tunggu';
        
        if (!in_array($level, ['dctk', 'sd', 'smp', 'sma'], true)) {
            abort(404, 'Jenjang tidak valid.');
        }

        $periods = Period::whereHas('academicYear', function ($query) {
            $query->where('is_active', true);
        })->get();

        $locations = Location::where('is_active', true)->get();

        return view(
            'waiting-list.form',
            compact(
                'level',
                'periods',
                'locations',
                'title'
            )
        );
    }
}