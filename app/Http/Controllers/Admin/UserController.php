<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('is_admin', false)->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);
        return redirect()->route('admin.users.index')->with('success', 'Đã cập nhật người dùng!');
    }

    public function toggleLock(User $user)
    {
        if ($user->is_admin) {
            return back()->withErrors(['error' => 'Không thể khóa tài khoản admin!']);
        }

        $user->update(['is_locked' => !$user->is_locked]);
        $message = $user->is_locked ? 'Đã khóa tài khoản!' : 'Đã mở khóa tài khoản!';
        return back()->with('success', $message);
    }
}