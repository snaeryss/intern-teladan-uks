<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ArtisanController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        $title = "Laravel Artisan Command";
        return view("artisan", compact('title'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function doCommand(Request $request): RedirectResponse
    {
        $data = $request->all();
        Artisan::call($data["command"]);
        return back()->with("success", "Success do artisan <b>".$data["command"]."</b>");
    }
}
