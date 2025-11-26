<?php

namespace App\Http\Controllers\ManageUser;

use App\Http\Controllers\Controller;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Throwable;

class RoleController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        $roles = Role::all();
        $title = 'Roles';
        return view('roles.index', compact('roles', 'title'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|min:3|max:255',
                'description' => 'required|string',
            ]);
            DB::transaction(static function () use ($data) {
                $data['guard_name'] = 'web';
                return Role::firstOrCreate($data);
            });
            return back()->with('success', 'Berhasil menambahkan Role baru');
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Gagal Menambahkan Role');
        }
    }

    /**
     * @param Request $request
     * @param Role $role
     * @return RedirectResponse
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|min:3|max:255',
                'description' => 'required|string',
            ]);
            $role->name = $data['name'];
            $role->description = $data['description'];
            DB::transaction(static function () use ($role) {
                $role->save();
            });
            return back()->with('success', 'Role Berhasil Diubah');
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Role Gagal Diperbaharui');
        }
    }
}
