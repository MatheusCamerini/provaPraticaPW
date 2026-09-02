@extends('layouts.app')

@section('title', 'Entrar')

@section('content')
    <h1>Entrar</h1>

    <form action="{{ route('user.login') }}" method="POST" class="form-card form-card-narrow">
        @csrf

        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            @error('email') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="password">Senha</label>
            <input type="password" id="password" name="password" required>
            @error('password') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-actions">
            <button type="submit">Entrar</button>
        </div>

        <p>Não tem conta? <a href="{{ route('registro') }}">Cadastre-se</a></p>
    </form>
@endsection
