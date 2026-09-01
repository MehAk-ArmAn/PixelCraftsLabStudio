<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->canManageSecurity(), 403);

        $query = AdminActivityLog::query()->with('user')->latest();

        if ($action = $request->query('action')) {
            $query->where('action', $action);
        }

        if ($term = trim((string) $request->query('q', ''))) {
            $query->where(fn ($q) => $q
                ->where('description', 'like', "%{$term}%")
                ->orWhere('user_name', 'like', "%{$term}%"));
        }

        return view('admin.activity.index', [
            'logs' => $query->paginate(40)->withQueryString(),
            'actions' => AdminActivityLog::query()->distinct()->orderBy('action')->pluck('action'),
            'q' => $request->query('q', ''),
            'action' => $action,
        ]);
    }
}
