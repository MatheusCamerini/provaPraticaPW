@extends('layouts.app')

@section('title', 'Adicionar Filme')

@section('content')
    <h1>Adicionar Filme</h1>

    <form action="{{ route('filmes.store') }}" method="POST" enctype="multipart/form-data" class="form-card">
        @csrf

        <div class="form-group">
            <label for="nome">Nome (máx. 30 caracteres)</label>
            <input type="text" id="nome" name="nome" maxlength="30" value="{{ old('nome') }}" required>
            <small class="char-counter" data-for="nome">0/30</small>
            @error('nome') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="sinopse">Sinopse</label>
            <textarea id="sinopse" name="sinopse" rows="4" required>{{ old('sinopse') }}</textarea>
            @error('sinopse') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="ano">Ano</label>
                <input type="number" id="ano" name="ano" min="1900" max="{{ date('Y') + 1 }}" value="{{ old('ano') }}" required>
                @error('ano') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="categoria">Categoria</label>
                <input type="text" id="categoria" name="categoria" value="{{ old('categoria') }}" required>
                @error('categoria') <span class="field-error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="trailer">Link do trailer (opcional)</label>
            <input type="url" id="trailer" name="trailer" value="{{ old('trailer') }}" placeholder="https://...">
            @error('trailer') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="capa">Capa (opcional)</label>
            <input type="file" id="capa" name="capa" accept="image/*" data-preview="capa-preview">
            <img id="capa-preview" class="image-preview" style="display:none">
            @error('capa') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-actions">
            <button type="submit">Salvar filme</button>
            <a href="{{ route('filmes.index') }}" class="button-secondary">Cancelar</a>
        </div>
    </form>
@endsection
