<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;

use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthManager $auth;

    public function __construct(AuthManager $auth)
    {
        $this->auth = $auth;
    }

    /**
     * show authentication page
     * @return View
     */
    public function index(): View
    {
        $title = "Login";
        return view('auth.index', compact('title'));
    }

    /**
     * do authentication for both student parent and teacher
     * @param AuthRequest $request
     * @return RedirectResponse
     */
    public function login(AuthRequest $request): RedirectResponse
    {
        $credentials = $request->validated();
        if ($this->auth->attempt($credentials)) {
            return redirect()->intended()->with(['success' => 'Login success!']);
        }
        return back()->with('error', 'Login fail, wrong username or password')
            ->with('username', $credentials['username']);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        $this->auth->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/auth')->with(['success' => 'You have been logged out!']);
    }
}
