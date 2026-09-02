@extends('layouts.app')

@section('title', 'Meu perfil')

@section('content')
    <div class="profile-header">
        <img src="{{ Storage::url($user->profile_picture) }}" alt="{{ $user->name }}" class="image-preview image-preview-round">
        <div>
            <h1>{{ $user->name }}</h1>
            <p>{{ $user->email }}</p>
        </div>
    </div>

    <h2>Meus filmes</h2>
    <div class="grid">
        @forelse ($filmes as $filme)
            <div class="card">
                <div class="poster-wrap">
                    <a href="{{ route('filmes.show', $filme->id) }}">
                        <img src="{{ Storage::url($filme->capa) }}" alt="Capa de {{ $filme->nome }}" class="card-img">
                    </a>
                    <span class="badge badge-year">{{ $filme->ano }}</span>
                    <span class="badge badge-category">{{ $filme->categoria }}</span>
                </div>
                <div class="card-body">
                    <h3><a href="{{ route('filmes.show', $filme->id) }}">{{ $filme->nome }}</a></h3>
                    <div class="card-actions">
                        <a href="{{ route('filmes.editForm', $filme->id) }}" class="button-secondary">Editar</a>
                        <form action="{{ route('filmes.destroy', $filme->id) }}" method="POST" class="inline-form" data-confirm="Mover este filme para a lixeira?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button-danger">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p>Você ainda não postou nenhum filme.</p>
        @endforelse
    </div>
@endsection
