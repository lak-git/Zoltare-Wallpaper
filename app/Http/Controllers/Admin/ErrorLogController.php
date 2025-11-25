<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ErrorLog;
use Illuminate\View\View;

class ErrorLogController extends Controller
{
    public function index(): View
    {
        return view('pages.admin.errors', [
            'logEntries' => ErrorLog::query()->orderByDesc('created_at')->paginate(50),
        ]);
    }
}

