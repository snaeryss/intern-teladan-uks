<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * show dashboard page
     * @return View
     */
    public function index(): View
    {
        $title = "Dashboard";
        return view('dashboard', compact('title'));
    }
}
