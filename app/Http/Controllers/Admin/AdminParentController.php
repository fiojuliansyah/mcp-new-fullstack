<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AdminParentController extends Controller
{
    public function index()
    {
        $count = User::where('account_type', 'parent')->count();       
        $users = User::where('account_type', 'parent')
            ->with([
                'children' => function ($query) {
                    $query->with([
                        'subscriptions.classrooms.subject', 
                    ]);
                }
            ])
            ->paginate(10);

        return view('admin.users.parents.index', compact('users','count'));
    }

    public function create()
    {
        $children = User::where('account_type', 'student')
                    ->where('parent_id', null)
                    ->get(); 
        
        return view('admin.users.parents.create', compact('children')); 
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'gender' => ['nullable', Rule::in(['male', 'female'])],
            'children_ids' => 'nullable|array', 
            'children_ids.*' => 'exists:users,id,account_type,student', 
        ]);

        $parent = new User();
        $parent->name = $validated['name'];
        $parent->slug = Str::slug($validated['name']);
        $parent->email = $validated['email'];
        $parent->password = Hash::make($validated['password']);
        $parent->gender = $validated['gender'] ?? null;
        $parent->account_type = 'parent';
        $parent->save();

        if (!empty($validated['children_ids'])) {
            $childrenToLink = User::whereIn('id', $validated['children_ids'])->get();
            
            foreach ($childrenToLink as $child) {
                $child->parent_id = $parent->id;
                $child->save();
            }
        }

        return redirect()
            ->route('admin.users.parents.index')
            ->with('success', 'Parent and linked children successfully added!');
    }

    public function edit($id)
    {
        $parent = User::where('account_type', 'parent')
                    ->with('children') 
                    ->findOrFail($id);

        $availableChildren = User::where('account_type', 'student')
            ->where(function ($query) use ($parent) {
                $query->whereNull('parent_id')
                    ->orWhere('parent_id', $parent->id);
            })
            ->get();

        return view('admin.users.parents.edit', compact('parent', 'availableChildren'));
    }

    public function update(Request $request, $id)
    {
        $parent = User::where('account_type', 'parent')->findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$parent->id, 
            'password' => 'nullable|min:6',
            'gender' => ['nullable', Rule::in(['male', 'female'])],
            'children_ids' => 'nullable|array', 
            'children_ids.*' => 'exists:users,id,account_type,student', 
        ]);

        $parent->name = $validated['name'];
        $parent->email = $validated['email'];
        if (!empty($validated['password'])) {
            $parent->password = Hash::make($validated['password']);
        }
        $parent->gender = $validated['gender'] ?? null;
        $parent->save();

        $newChildrenIds = $validated['children_ids'] ?? [];
        
        User::where('parent_id', $parent->id)
            ->whereNotIn('id', $newChildrenIds)
            ->update(['parent_id' => null]);

        if (!empty($newChildrenIds)) {
            User::whereIn('id', $newChildrenIds)
                ->where('account_type', 'student')
                ->update(['parent_id' => $parent->id]);
        }

        return redirect()
            ->route('admin.users.parents.index')
            ->with('success', 'Parent and linked children successfully updated!');
    }

    public function show($id)
     {
        $parent = User::where('account_type', 'parent')
            ->with([
                'children' => fn($q) => $q->with([
                    'subscriptions' => fn($sq) => $sq->with([
                        'plan', 
                        'classrooms.subject',
                    ])
                ])
            ])
            ->findOrFail($id);

        return view('admin.users.parents.show', compact('parent'));
     }
}
