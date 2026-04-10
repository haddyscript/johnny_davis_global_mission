<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Carbon;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');

        $admins = User::query()
            ->when($search, fn ($q) => $q->where(fn ($q2) =>
                $q2->where('name', 'like', "%{$search}%")
                   ->orWhere('email', 'like', "%{$search}%")
            ))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total'       => User::count(),
            'verified'    => User::whereNotNull('email_verified_at')->count(),
            'this_month'  => User::where('created_at', '>=', Carbon::now()->startOfMonth())->count(),
        ];

        return view('admin.admins.index', compact('admins', 'stats', 'search'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'              => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'password_confirmation' => ['required'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'], // auto-hashed via model cast
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Admin created successfully.',
                'user'    => [
                    'id'      => $user->id,
                    'name'    => $user->name,
                    'email'   => $user->email,
                    'joined'  => $user->created_at->format('M j, Y'),
                    'initials' => collect(explode(' ', $user->name))
                        ->map(fn ($w) => strtoupper($w[0] ?? ''))
                        ->take(2)
                        ->implode(''),
                ],
            ]);
        }

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin "' . $user->name . '" created successfully.');
    }

    public function update(Request $request, User $admin)
    {
        $rules = [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $admin->id],
        ];

        if ($request->filled('password')) {
            $rules['password']              = ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()];
            $rules['password_confirmation'] = ['required'];
        }

        $data = $request->validate($rules);

        $admin->name  = $data['name'];
        $admin->email = $data['email'];

        if ($request->filled('password')) {
            $admin->password = $data['password']; // auto-hashed
        }

        $admin->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Admin updated successfully.',
                'user'    => [
                    'id'      => $admin->id,
                    'name'    => $admin->name,
                    'email'   => $admin->email,
                    'joined'  => $admin->created_at->format('M j, Y'),
                    'initials' => collect(explode(' ', $admin->name))
                        ->map(fn ($w) => strtoupper($w[0] ?? ''))
                        ->take(2)
                        ->implode(''),
                ],
            ]);
        }

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin updated successfully.');
    }

    public function destroy(Request $request, User $admin)
    {
        if ($admin->id === auth()->id()) {
            $msg = 'You cannot delete your own account.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 403);
            }
            return back()->with('error', $msg);
        }

        if (User::count() === 1) {
            $msg = 'Cannot delete the last administrator account.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 403);
            }
            return back()->with('error', $msg);
        }

        $name = $admin->name;
        $admin->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "Admin \"{$name}\" deleted."]);
        }

        return redirect()->route('admin.admins.index')
            ->with('success', "Admin \"{$name}\" has been removed.");
    }
}
