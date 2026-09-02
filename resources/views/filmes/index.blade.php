@extends('layouts.app')

@section('title', 'Filmes')

@section('content')
    <h1>Catálogo de Filmes</h1>

    <form action="{{ route('filmes.search') }}" method="GET" class="search-panel">
        <div class="form-row">
            <div class="form-group">
                <label for="nome">Nome do filme</label>
                <input type="text" id="nome" name="nome" value="{{ request('nome') }}">
            </div>
            <div class="form-group">
                <label for="user_name">Postado por</label>
                <input type="text" id="user_name" name="user_name" value="{{ request('user_name') }}">
            </div>
            <div class="form-group">
                <label for="ano">Ano</label>
                <input type="number" min="1900" max="{{ date('Y') + 1 }}" id="ano" name="ano" value="{{ request('ano') }}">
            </div>
            <div class="form-group">
                <label for="categoria">Categoria</label>
                <input type="text" id="categoria" name="categoria" value="{{ request('categoria') }}">
            </div>
            <div class="form-group form-group-wide">
                <label for="sinopse">Sinopse</label>
                <input type="text" id="sinopse" name="sinopse" value="{{ request('sinopse') }}">
            </div>
        </div>
        <div class="form-actions">
            <button type="submit">Pesquisar</button>
            <a href="{{ route('filmes.index') }}" class="button-secondary">Limpar</a>
        </div>
    </form>

    <div class="grid">
        @forelse ($filmes as $filme)
            <div class="card">
                <div class="poster-wrap">
                    <a href="{{ route('filmes.show', $filme->id) }}">
                        <img src="{{ Storage::url($filme->capa)}}" alt="Capa de {{ $filme->nome }}" class="card-img">
                    </a>
                    <span class="badge badge-year">{{ $filme->ano }}</span>
                    <span class="badge badge-category">{{ $filme->categoria }}</span>
                </div>
                <div class="card-body">
                    <h3><a href="{{ route('filmes.show', $filme->id) }}">{{ $filme->nome }}</a></h3>
                    <p class="card-text">{{ Str::limit($filme->sinopse, 100) }}</p>
                    <p class="card-author">Postado por {{ $filme->user->name ?? 'Usuário removido' }}</p>

                    @if ($filme->trailer)
                        <a href="{{ $filme->trailer }}" target="_blank" rel="noopener" class="button-secondary" style="width: fit-content;">Trailer</a>
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
                </div>
            </div>
        @empty
            <p>Nenhum filme encontrado.</p>
        @endforelse
    </div>
@endsection
