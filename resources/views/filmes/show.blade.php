@extends('layouts.app')

@section('title', $filme->nome)

@section('content')
    <div class="show-panel">
        <div class="show-poster-wrap">
            <img src="{{ $filme->capa_url }}" alt="Capa de {{ $filme->nome }}" class="show-img">
        </div>
        <div class="show-info">
            <div class="show-badges">
                <span class="badge-inline badge-year">{{ $filme->ano }}</span>
                <span class="badge-inline badge-category">{{ $filme->categoria }}</span>
            </div>

            <h1>{{ $filme->nome }}</h1>
            <p class="card-author">Postado por {{ $filme->user->name ?? 'Usuário removido' }}</p>
            <p>{{ $filme->sinopse }}</p>

            @if ($filme->trailer)
                <a href="{{ $filme->trailer }}" target="_blank" rel="noopener" class="button-secondary">Assistir trailer</a>
            @endif

            @auth
                @if ($filme->user_id === auth()->id())
                    <div class="card-actions">
                        <a href="{{ route('filmes.editForm', $filme->id) }}" class="button-secondary">Editar</a>
                        <form action="{{ route('filmes.destroy', $filme->id) }}" method="POST" class="inline-form" data-confirm="Mover este filme para a lixeira?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button-danger">Excluir</button>
                        </form>
                    </div>
                @endif
            @endauth

            <a href="{{ route('filmes.index') }}" class="button-secondary">Voltar</a>
        </div>
    </div>
@endsection