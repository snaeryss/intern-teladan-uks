<?php

namespace App\Http\Controllers;

use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{

    private AuthManager $auth;

    public function __construct(AuthManager $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @return View
     */
    public function index(): View
    {
        $user = $this->auth->user();
        $title = "Account";
        return view('account', compact('user', 'title'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|min:3',
            'old_password' => 'required',
            'password' => 'required|min:6',
        ]);

        $user = $this->auth->user();

        if (!Hash::check($data['old_password'], $user->password)) {
            return back()->withErrors(['old_password' => 'Kata sandi yang diberikan tidak cocok dengan kata sandi Anda saat ini.']);
        }

        $user->update([
            'name' => $data['name'],
            'password' => Hash::make($data['password']),
            'secret' => Crypt::encrypt('-'),
        ]);

        return back()->with('success', 'Password berhasil diperbaharui.');
    }
}
