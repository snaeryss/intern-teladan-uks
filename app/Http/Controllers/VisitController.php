<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Visit;


class VisitController extends Controller
{
    public function index()
    {
        $title = 'Kunjungan UKS';
        $visits = Visit::with('student')
            ->orderBy('date', 'desc')
            ->orderBy('arrival_time', 'desc')
            ->paginate(15);

        return view('visits.index', compact('title','visits'));
    }

    public function create(): View
    {
        $title = 'Tambah Kunjungan';
        return view('visits.form', compact('title'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'day' => 'required|string',
            'date' => 'required|date',
            'arrival_time' => 'required',
            'departure_time' => 'nullable',
            'complaint' => 'required|string',
            'treatment' => 'required|string',
            'outcome_notes' => 'required|string',
        ], [
            'student_id.required' => 'Siswa harus dipilih',
            'student_id.exists' => 'Siswa tidak ditemukan',
            'day.required' => 'Hari harus diisi',
            'date.required' => 'Tanggal periksa harus diisi',
            'date.date' => 'Format tanggal tidak valid',
            'arrival_time.required' => 'Jam datang harus diisi',
            'complaint.required' => 'Keluhan harus diisi',
            'treatment.required' => 'Penanganan harus diisi',
            'outcome_notes.required' => 'Hasil harus diisi',
        ]);

        Visit::create($validated);

        return redirect()->route('visits.index')
            ->with('success', 'Data kunjungan berhasil ditambahkan');
    }

    public function edit(Visit $visit): View
    {
        $title = 'Edit Kunjungan';
        $visit->load('student');
        return view('visits.form', compact('title', 'visit'));
    }

    public function update(Request $request, Visit $visit)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'day' => 'required|string',
            'date' => 'required|date',
            'arrival_time' => 'required',
            'departure_time' => 'nullable',
            'complaint' => 'required|string',
            'treatment' => 'required|string',
            'outcome_notes' => 'required|string',
        ], [
            'student_id.required' => 'Siswa harus dipilih',
            'student_id.exists' => 'Siswa tidak ditemukan',
            'day.required' => 'Hari harus diisi',
            'date.required' => 'Tanggal periksa harus diisi',
            'date.date' => 'Format tanggal tidak valid',
            'arrival_time.required' => 'Jam datang harus diisi',
            'complaint.required' => 'Keluhan harus diisi',
            'treatment.required' => 'Penanganan harus diisi',
            'outcome_notes.required' => 'Hasil harus diisi',
        ]);

        $visit->update($validated);

        return redirect()->route('visits.index')
            ->with('success', 'Data kunjungan berhasil diupdate');
    }

    public function destroy(Visit $visit)
    {
        $visit->delete();

        return redirect()->route('visits.index')
            ->with('success', 'Data kunjungan berhasil dihapus.');
    }
}
