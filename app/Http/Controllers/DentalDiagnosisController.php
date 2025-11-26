<?php

namespace App\Http\Controllers;

use App\Models\DentalDiagnosis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DentalDiagnosisController extends Controller
{
    public function index()
{
    $diagnoses = DentalDiagnosis::orderBy('code')->get();
    
    return view('dental-diagnoses.index', [
        'title' => 'Manajemen Diagnosis Gigi',
        'diagnoses' => $diagnoses
    ]);
}

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:dental_diagnoses,code',
            'description' => 'required|string|max:1000',
        ], [
            'code.required' => 'Kode diagnosis wajib diisi',
            'code.unique' => 'Kode diagnosis sudah digunakan',
            'code.max' => 'Kode diagnosis maksimal 10 karakter',
            'description.required' => 'Deskripsi wajib diisi',
            'description.max' => 'Deskripsi maksimal 1000 karakter',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DentalDiagnosis::create([
                'code' => strtoupper($request->code),
                'description' => $request->description,
            ]);

            return redirect()->route('dental-diagnoses.index')
                ->with('success', 'Diagnosis gigi berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, DentalDiagnosis $dentalDiagnosis)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:dental_diagnoses,code,' . $dentalDiagnosis->id,
            'description' => 'required|string|max:1000',
        ], [
            'code.required' => 'Kode diagnosis wajib diisi',
            'code.unique' => 'Kode diagnosis sudah digunakan',
            'code.max' => 'Kode diagnosis maksimal 10 karakter',
            'description.required' => 'Deskripsi wajib diisi',
            'description.max' => 'Deskripsi maksimal 1000 karakter',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            $dentalDiagnosis->update([
                'code' => strtoupper($request->code),
                'description' => $request->description,
            ]);

            return redirect()->route('dental-diagnoses.index')
                ->with('success', 'Diagnosis gigi berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(DentalDiagnosis $dentalDiagnosis)
    {
        try {
            if ($dentalDiagnosis->dcuDiagnoses()->exists()) {
                return redirect()->back()
                    ->with('error', 'Diagnosis tidak dapat dihapus karena sedang digunakan dalam data pemeriksaan');
            }

            $dentalDiagnosis->delete();

            return redirect()->route('dental-diagnoses.index')
                ->with('success', 'Diagnosis gigi berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}