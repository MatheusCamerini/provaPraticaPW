<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function showLoginForm()
    {
        return view('user.login');
    }

    public function showRegisterForm()
    {
        return view('user.register');
    }

    public function profile()
    {
        $user = Auth::user();
        $filmes = $user->filmes()->latest()->get();
        return view('profile', compact('user', 'filmes'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $user->tokens()->delete();
            $user->createToken('auth_token');

            return redirect()->route('filmes.index')->with('success', 'Login realizado com sucesso!');
        }

        return back()->withErrors(['email' => 'Credenciais inválidas'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::user()?->tokens()->delete();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout realizado com sucesso!');
    }

    public function register(Request $request)
    {
        $credentials = $request->validate([
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'email' => 'required|email|unique:users,email',
            'name' => 'required|max:255',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'profile_picture' => $request->file('profile_picture')
                ? $request->file('profile_picture')->store('profile_pictures', 'public')
                : null,
            'email' => $credentials['email'],
            'name' => $credentials['name'],
            'password' => bcrypt($credentials['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $user->createToken('auth_token');

        return redirect()->route('filmes.index')->with('success', 'Cadastro realizado com sucesso!');
    }
}