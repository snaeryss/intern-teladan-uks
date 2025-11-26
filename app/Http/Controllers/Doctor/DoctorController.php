<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StoreDoctorRequest;
use App\Http\Requests\Doctor\UpdateDoctorRequest;
use App\Models\Doctor;
use App\Models\DoctorAccount;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DoctorController extends Controller
{
    public function index(): View
    {
        $title = 'Doctors';
        $doctors = Doctor::orderBy('name')->get();
        
        return view('doctors.index', compact('title', 'doctors'));
    }
    
    public function create(): View
    {
        $title = 'Create Doctor';
        return view('doctors.create', compact('title'));
    }
    
    public function store(StoreDoctorRequest $request): RedirectResponse
    {
        $data = $request->validated();
        
        try {
            DB::transaction(function () use ($data) {
                $data['id'] = Str::uuid();
                
                // Handle signature upload
                if (isset($data['signature'])) {
                    $path = $data['signature']->store('signatures', 'public');
                    $data['signature'] = $path;
                }
                
                Doctor::create($data);
            });
            
            return redirect()->route('doctor')->with('success', 'Berhasil Menambah Data Dokter');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return back()->with('error', 'Gagal Menambah Data Dokter');
        }
    }
    
    public function show(Doctor $doctor): View
    {
        $title = 'Doctor Detail';
        $account = DoctorAccount::where('doctor_id', $doctor->id)->first();
        
        return view('doctors.detail', compact('title', 'doctor', 'account'));
    }
    
    public function update(UpdateDoctorRequest $request, Doctor $doctor): RedirectResponse
    {
        $data = $request->validated();
        
        try {
            DB::transaction(function () use ($doctor, $data) {
                // Handle signature upload
                if (isset($data['signature'])) {
                    // Delete old signature
                    if ($doctor->signature) {
                        Storage::disk('public')->delete($doctor->signature);
                    }
                    
                    $path = $data['signature']->store('signatures', 'public');
                    $data['signature'] = $path;
                }
                
                $doctor->update($data);
            });
            
            return back()->with('success', 'Berhasil Memperbaharui Data Dokter');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return back()->with('error', 'Gagal Memperbaharui Data Dokter');
        }
    }
    
    public function destroy(Doctor $doctor): RedirectResponse
    {
        try {
            DB::transaction(function () use ($doctor) {
                // Toggle active status instead of hard delete
                $doctor->update(['is_active' => !$doctor->is_active]);
            });
            
            $status = $doctor->is_active ? 'diaktifkan' : 'dinonaktifkan';
            return back()->with('success', "Dokter berhasil {$status}");
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return back()->with('error', 'Gagal mengubah status dokter');
        }
    }
}