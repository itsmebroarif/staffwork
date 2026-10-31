<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display the activity log for Super Admin monitoring.
     */
    public function index(Request $request): View
    {
        $query = ActivityLog::with('user')->latest('created_at');

        if ($request->filled('q')) {
            $keyword = $request->string('q')->toString();

            $query->where(function ($sub) use ($keyword) {
                $sub->where('description', 'like', '%'.$keyword.'%')
                    ->orWhere('action', 'like', '%'.$keyword.'%')
                    ->orWhere('ip_address', 'like', '%'.$keyword.'%')
                    ->orWhereHas('user', fn ($user) => $user->where('name', 'like', '%'.$keyword.'%'));
            });
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->integer('user_id'));
        }

        if ($request->filled('action')) {
            $query->where('action', $request->string('action')->toString());
        }

        return view('pages.activity.index', [
            'logs' => $query->paginate(15)->withQueryString(),
            'users' => User::orderBy('name')->get(['id', 'name', 'role']),
            'actions' => ActivityLog::query()->select('action')->distinct()->orderBy('action')->pluck('action'),
            'totalToday' => ActivityLog::whereDate('created_at', now()->toDateString())->count(),
            'totalAll' => ActivityLog::count(),
        ]);
    }
}
