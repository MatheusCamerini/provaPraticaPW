<?php

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // Cria o token de acesso
            $token = $user->createToken('auth_token')->plainTextToken;
            $request->session()->put('access_token', $token);
            $user->tokens()->delete();

            return redirect()->route('dashboard')->with('success', 'Login realizado com sucesso!');
        }

        return response()->json(['message' => 'Credenciais inválidas'], 401);
    }
    public function logout(Request $request)
    {
        $request->session()->forget('access_token');
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logout realizado com sucesso!');
    }
    public function singIn(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('name', $credentials['name'])->first();

        if ($user) {
            return response()->json(['message' => 'Usuário já existe'], 400);
        }

        $user = User::create([
            'name' => $credentials['name'],
            'password' => bcrypt($credentials['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        $request->session()->put('access_token', $token);

        return redirect()->route('dashboard')->with('success', 'Cadastro realizado com sucesso!');
    }
}