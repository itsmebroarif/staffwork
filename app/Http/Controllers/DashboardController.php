<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Material;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index(): View
    {
        $user = Auth::user();
        $totalUsers = User::count();
        $recentUsers = User::latest()->take(5)->get();
        $totalSuperAdmins = User::where('role', 'superadmin')->count();
        $totalGuru = User::where('role', 'guru')->count();
        $totalMurid = User::where('role', 'murid')->count();
        $totalMaterials = Material::count();
        $totalExams = Exam::count();

        return view('pages.Dashboard', [
            'user' => $user,
            'totalUsers' => $totalUsers,
            'recentUsers' => $recentUsers,
            'totalSuperAdmins' => $totalSuperAdmins,
            'totalGuru' => $totalGuru,
            'totalMurid' => $totalMurid,
            'totalMaterials' => $totalMaterials,
            'totalExams' => $totalExams,
        ]);
    }
}
