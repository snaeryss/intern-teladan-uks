<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Lokasi Pemeriksaan';
        
        $query = Location::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 10);
        $locations = $query->paginate($perPage)->withQueryString();
        
        return view('locations.index', compact('locations', 'title'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        Location::create($validated);

        return redirect()->route('locations')
            ->with('success', 'Lokasi berhasil ditambahkan');
    }

    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        $location->update($validated);

        return redirect()->route('locations')
            ->with('success', 'Lokasi berhasil diperbarui');
    }

    public function destroy(Location $location)
    {
        $location->delete();

        return redirect()->route('locations')
            ->with('success', 'Lokasi berhasil dihapus');
    }
}