<?php

namespace App\Http\Controllers\Admin;

use App\Models\Form;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AdminStudentController extends Controller
{
    public function index()
    {
        $count = User::where('account_type', 'student')->count();
        $users = User::where('account_type', 'student')->paginate(10);
        return view('admin.users.students.index', compact('users','count'));
    }

    public function create()
    {
        $parents = User::where('account_type', 'parent')->get();
        $forms = Form::all();
        return view('admin.users.students.create', compact('parents','forms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'gender' => ['nullable', Rule::in(['male', 'female'])],
            'level' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:users,id',
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->gender = $validated['gender'] ?? null;
        $user->level = $validated['level'] ?? null;
        $user->parent_id = $validated['parent_id'] ?? null;
        $user->account_type = 'student';
        $user->save();

        return redirect()
            ->route('admin.users.student')
            ->with('success', 'Student successfully added!');
    }


    public function edit($id)
    {
        $user = User::findOrFail($id);
        $parents = User::where('account_type', 'parent')->get();
        $forms = Form::all();

        return view('admin.users.students.edit', compact('user', 'parents', 'forms'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'gender' => 'nullable|in:male,female',
            'level' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:users,id',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->gender = $validated['gender'] ?? null;
        $user->level = $validated['level'] ?? null;
        $user->parent_id = $validated['parent_id'] ?? null;
        $user->save();

        return redirect()
            ->route('admin.users.student')
            ->with('success', 'Student updated successfully!');
    }

    public function show($id)
    {
        $user = User::with([
            'parent',
            'subscriptions.classrooms.subject',
            'subscriptions.plan'
        ])->findOrFail($id);

        return view('admin.users.students.show', compact('user'));
    }

}
