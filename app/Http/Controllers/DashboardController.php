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

        $roleCounts = User::query()
            ->selectRaw('role, COUNT(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role');

        $recentUsers = User::latest()->take(5)->get();

        return view('pages.Dashboard', [
            'user' => $user,
            'totalUsers' => $roleCounts->sum(),
            'recentUsers' => $recentUsers,
            'totalSuperAdmins' => (int) $roleCounts->get('superadmin', 0),
            'totalGuru' => (int) $roleCounts->get('guru', 0),
            'totalMurid' => (int) $roleCounts->get('murid', 0),
            'totalMaterials' => Material::count(),
            'totalExams' => Exam::count(),
        ]);
    }
}
