<?php

namespace App\Http\Controllers;

use App\Models\ActionLog;
use Illuminate\Http\Request;

/**
 * Admin log viewer.
 *
 * Accès protégé par le middleware 'admin' (voir routes/web.php).
 */
class LogController extends Controller
{
    public function index(Request $request)
    {
        $action   = $request->input('action');
        $dateFrom = $request->input('date_from');
        $dateTo   = $request->input('date_to');

        $actions = ActionLog::distinct()->orderBy('action')->pluck('action');

        $logs = ActionLog::with('user')
            ->when($action, fn ($q) => $q->where('action', $action))
            ->when($dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('logs.index', compact('logs', 'actions', 'action', 'dateFrom', 'dateTo'));
    }
}
