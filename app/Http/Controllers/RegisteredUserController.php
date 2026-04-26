<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    public function index(): void {}

    public function create(): Response
    {
        $route = route('create.user');
        return Inertia::render('Auth/Register')->with('route', $route);
    }

    public function store(Request $request): RedirectResponse
    {
        $valid = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        auth()->login($user);
        return redirect()->route('home')->with('message', 'User created successfully');
    }

    public function show(User $user) {}

    public function edit(User $user) {}

    public function update(Request $request, User $user) {}

    public function destroy(User $user) {}

    public function logout(Request $request)
    {
        auth()->logout();
        return redirect()->route('home');
    }

    public function login()
    {
        $route = route('login.user');
        return Inertia::render('Auth/Login')->with('route', $route);
    }

    public function authorize(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        if (auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('home');
        }
        return redirect()->back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }
}
