@extends('layouts.app')

@section('title', 'Criar conta')

@section('content')
    <h1>Criar conta</h1>

    <form action="{{ route('user.register') }}" method="POST" enctype="multipart/form-data" class="form-card form-card-narrow">
        @csrf

        <div class="form-group">
            <label for="name">Nome</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            @error('name') <span class="field-error">{{ $message }}</span> @enderror
        </div>

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

        <div class="form-group">
            <label for="password_confirmation">Confirmar senha</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
            <small class="field-hint" id="password-match-hint"></small>
        </div>

        <div class="form-group">
            <label for="profile_picture">Foto de perfil (opcional)</label>
            <input type="file" id="profile_picture" name="profile_picture" accept="image/*" data-preview="avatar-preview">
            <img id="avatar-preview" class="image-preview image-preview-round" style="display:none">
            @error('profile_picture') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-actions">
            <button type="submit">Criar conta</button>
        </div>

        <p>Já tem conta? <a href="{{ route('login') }}">Entrar</a></p>
    </form>
@endsection
