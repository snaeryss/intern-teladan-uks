<?php

namespace App\Http\Controllers\ManageUser;

use App\Http\Controllers\Controller;

use App\Http\Requests\ManageUser\StoreUserRequest;
use App\Http\Requests\ManageUser\UpdateUserRequest;
use App\Models\User;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Throwable;

class ManageUserController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        $title = "Manage User";
        $accounts = User::admin()->get();
        return view('manage-users.index', compact('title', 'accounts'));
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $title = "Create User";
        return view('manage-users.create', compact('title'));
    }

    /**
     * @param StoreUserRequest $request
     * @return RedirectResponse
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $user = DB::transaction(static function () use ($data) {
                $data['password'] = Hash::make($data['password']);
                return User::firstOrCreate($data);
            });
            return redirect(route('manage-account.detail', $user->id))
                ->with('success', 'Berhasil menambahkan User baru');
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Gagal Menambahkan User');
        }
    }

    /**
     * @param User $user
     * @return View
     */
    public function show(User $user): View
    {
        $title = "Detail User";
        $roles = Role::all();
        return view(
            'manage-users.detail',
            compact('user', 'roles', 'title')
        );
    }

    /**
     * @param UpdateUserRequest $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        try {
            $data = $request->validated();
            $user->name = $data['name'];
            $user->is_active = $data['status'];
            if (isset($data['password'])) {
                $user->password = Hash::make($data['password']);
            }
            DB::transaction(static function () use ($user) {
                $user->save();
            });
            return back()->with('success', 'Data User Berhasil Diubah');
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Data User Gagal Diperbaharui');
        }
    }
}

