<?php

namespace App\Http\Controllers\Admin;

use App\Models\Form;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function parent()
    {
        $users = User::where('account_type', 'parent')->paginate(10);
        return view('admin.users.parents.index', compact('users'));
    }

    public function tutor()
    {
        $users = User::where('account_type', 'tutor')->paginate(10);
        return view('admin.users.tutors.index', compact('users'));
    }
}
