<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Support;
use Illuminate\Http\Request;

class AdminSupportController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $supports = Support::with(['user', 'assignedTo'])
            ->when($search, function ($query, $search) {
                $query->where('ticket_number', 'like', "%{$search}%")
                      ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"))
                      ->orWhere('category', 'like', "%{$search}%")
                      ->orWhere('status', 'like', "%{$search}%");
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.supports.index', compact('supports', 'search'));
    }

    public function show(Support $support)
    {
        $support->load(['user', 'assignedTo']);
        return view('admin.supports.show', compact('support'));
    }
}
