<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class ReportController extends Controller
{
    /**
     * Display the bug report / feature request page.
     */
    public function index(): View
    {
        return view('pages.report.index');
    }
}
