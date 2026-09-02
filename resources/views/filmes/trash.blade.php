@extends('layouts.app')

@section('title', 'Lixeira')

@section('content')
    <h1>Lixeira</h1>
    <p>Aqui aparecem apenas os filmes que você excluiu.</p>

    <div class="grid">
        @forelse ($filmes as $filme)
            <div class="card">
                <div class="poster-wrap">
                    <img src="{{ $filme->capa_url }}" alt="Capa de {{ $filme->nome }}" class="card-img">
                    <span class="badge badge-year">{{ $filme->ano }}</span>
                    <span class="badge badge-category">{{ $filme->categoria }}</span>
                </div>
                <div class="card-body">
                    <h3>{{ $filme->nome }}</h3>
                    <p class="card-text">{{ Str::limit($filme->sinopse, 100) }}</p>

                    <div class="card-actions">
                        <form action="{{ route('filmes.restore', $filme->id) }}" method="POST" class="inline-form">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="button-secondary">Restaurar</button>
                        </form>
                        <form action="{{ route('filmes.forceDelete', $filme->id) }}" method="POST" class="inline-form" data-confirm="Excluir permanentemente? Esta ação não pode ser desfeita.">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button-danger">Excluir permanentemente</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p>Sua lixeira está vazia.</p>
        @endforelse
    </div>
@endsection
