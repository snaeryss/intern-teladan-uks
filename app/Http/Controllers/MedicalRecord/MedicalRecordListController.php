<?php

namespace App\Http\Controllers\MedicalRecord;

use App\Http\Controllers\Controller;
use App\Models\Dcu;
use App\Models\Mcu;
use App\Models\Period;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MedicalRecordListController extends Controller
{
    /**
     * Display all waiting list for medical examinations (DCU, MCU, SCR).
     * Filtered based on user role:
     * - SuperVisor: Can see all (DCU, MCU, SCR)
     * - Doktor (General Doctor): Can only see MCU & SCR
     * - Doktor Gigi (Dental Doctor): Can only see DCU
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $title = "Semua Daftar Tunggu";
        $user = auth()->user();
        
        // Determine allowed types based on user role
        $allowedTypes = $this->getAllowedTypesByRole($user);
        
        // Get type from request, default to first allowed type
        $type = $request->get('type', $allowedTypes[0] ?? 'dcu');
        
        // Validate if user is allowed to access this type
        if (!in_array($type, $allowedTypes)) {
            abort(403, 'Anda tidak memiliki akses ke daftar tunggu ini.');
        }

        $activePeriods = Period::whereHas('academicYear', function ($query) {
            $query->where('is_active', true);
        })->pluck('id');

        $waitingList = collect();

        if ($type === 'dcu') {
            $dcuData = Dcu::with(['student', 'period'])
                ->whereIn('period_id', $activePeriods)
                ->unfinished()
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($dcu) {
                    return [
                        'id' => $dcu->id,
                        'student_id' => $dcu->student_id,
                        'student_name' => $dcu->student->name,
                        'student_nis' => $dcu->student->nis,
                        'class' => $dcu->student->class,
                        'period' => $dcu->period->display_name,
                        'period_id' => $dcu->period_id,
                        'registered_at' => $dcu->created_at->format('H:i, d-m-Y'),
                        'is_finish' => $dcu->is_finish,
                        'type' => 'DCU',
                        'level_display' => $dcu->student->school_level->getShortName(),
                        'level_route' => $dcu->student->school_level->getGroupLevel(),
                    ];
                });

            $waitingList = $dcuData;
        } elseif ($type === 'mcu' || $type === 'scr') {
            $mcuData = Mcu::with(['student', 'period'])
                ->whereIn('period_id', $activePeriods)
                ->unfinished()
                ->when($type === 'scr', function ($query) {
                    $query->whereHas('period', function ($q) {
                        $q->whereRaw('UPPER(name) = ?', ['SCR']);
                    });
                })
                ->when($type === 'mcu', function ($query) {
                    $query->whereHas('period', function ($q) {
                        $q->whereRaw('UPPER(name) = ?', ['MCU']);
                    });
                })
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($mcu) {
                    return [
                        'id' => $mcu->mcu_id,
                        'student_id' => $mcu->student_id,
                        'student_name' => $mcu->student->name,
                        'student_nis' => $mcu->student->nis,
                        'class' => $mcu->student->class,
                        'period' => $mcu->period->display_name,
                        'period_id' => $mcu->period_id,
                        'registered_at' => $mcu->created_at->format('H:i, d-m-Y'),
                        'is_finish' => $mcu->is_finish,
                        'type' => strtoupper($mcu->period->name),
                        'level_display' => $mcu->student->school_level->getShortName(),
                        'level_route' => $mcu->student->school_level->getGroupLevel(),
                    ];
                });

            $waitingList = $mcuData;
        }

        return view('waiting-list.all', compact('title', 'waitingList', 'type', 'allowedTypes'));
    }

    /**
     * Get allowed types based on user role
     * 
     * @param \App\Models\User $user
     * @return array
     */
    private function getAllowedTypesByRole($user): array
    {
        // SuperVisor can see all types
        if ($user->hasRole('SuperVisor')) {
            return ['scr', 'mcu', 'dcu'];
        }
        
        // General Doctor can only see MCU & SCR
        if ($user->hasRole('Doktor')) {
            return ['scr', 'mcu'];
        }
        
        // Dental Doctor can only see DCU
        if ($user->hasRole('Doktor Gigi')) {
            return ['dcu'];
        }
        
        // Default: show all for Principal and PIC
        return ['scr', 'mcu', 'dcu'];
    }
}