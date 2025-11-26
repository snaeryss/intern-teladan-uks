<?php

namespace App\Http\Controllers\MedicalRecord;

use App\Http\Controllers\Controller;
use App\Models\Dcu;
use App\Models\Mcu;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class RecordHistoriesController extends Controller
{
    public function index(Request $request): View
    {
        $title = "Riwayat Pemeriksaan";
        $defaultType = ($request->user()->hasRole(['SuperVisior', 'Doktor'])) ? 'scr' : 'dcu';
        $type = $request->get('type', $defaultType);

        $histories = collect();

        if ($type === 'dcu') {
            $dcuData = Dcu::with(['student', 'period'])
                ->finished()
                ->orderBy('updated_at', 'desc')
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
                        'period_is_active' => $dcu->period->is_active ?? false,
                        'finished_at' => $dcu->updated_at->format('H:i, d-m-Y'),
                        'type' => 'DCU',
                        'level_display' => $dcu->student->school_level->getShortName(),
                        'level_route' => $dcu->student->school_level->getGroupLevel(),
                    ];
                });

            $histories = $dcuData;
        } elseif ($type === 'mcu' || $type === 'scr') {
            $mcuData = Mcu::with(['student', 'period'])
                ->finished() 
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
                ->orderBy('updated_at', 'desc')
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
                        'period_is_active' => $mcu->period->is_active ?? false,
                        'finished_at' => $mcu->updated_at->format('H:i, d-m-Y'),
                        'type' => strtoupper($mcu->period->name),
                        'level_display' => $mcu->student->school_level->getShortName(),
                        'level_route' => $mcu->student->school_level->getGroupLevel(),
                    ];
                });

            $histories = $mcuData;
        }

        return view('record-histories.index', compact('title', 'histories', 'type'));
    }

}
