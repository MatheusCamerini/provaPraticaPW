<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Filme;
use Illuminate\Support\Facades\Storage;

class FilmeController extends Controller{
    public function index(){
        $filmes = Filme::all();
        return view('filmes.index', compact('filmes'));
    }
    public function create(){
        return view('filmes.create');
    }
    public function trash(){
        $filmes = Filme::onlyTrashed()->where('user_id', auth()->id())->latest()->get();
        return view('filmes.trash', compact('filmes'));
    }
    public function editForm($id){
        $filme = Filme::findOrFail($id);
        if ($filme->user_id !== auth()->id()) { abort(403, 'Acesso não autorizado'); }
        return view('filmes.editForm', compact('filme'));
    }
    public function store(Request $request){
        $request->validate([
            'nome' => 'required|max:30',
            'sinopse' => 'required',
            'ano' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'categoria' => 'required|max:255',
            'trailer' => 'nullable|url|max:2000',
            'capa' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $filme = new Filme();
        $filme->user_id = auth()->id();
        $filme->nome = $request->input('nome');
        $filme->sinopse = $request->input('sinopse');
        $filme->ano = $request->input('ano');
        $filme->categoria = $request->input('categoria');
        $filme->trailer = $request->input('trailer');

        if ($request->hasFile('capa')) {
            $capaPath = $request->file('capa')->store('capas', 'public');
            $filme->capa = $capaPath;
        }

        $filme->save();

        return redirect()->route('filmes.index')->with('success', 'Filme criado com sucesso!');
    }
    public function destroy($id){
        $filme = Filme::findOrFail($id);
        if ($filme->user_id !== auth()->id()) { abort(403, 'Acesso não autorizado'); }
        $filme->delete();
        return redirect()->route('filmes.index')->with('success', 'Filme deletado com sucesso!');
    }
    public function forceDelete($id){
        $filme = Filme::withTrashed()->findOrFail($id);
        if ($filme->user_id !== auth()->id()) { abort(403, 'Acesso não autorizado'); }
        if ($filme->capa) {
            Storage::disk('public')->delete($filme->capa);
        }
        $filme->forceDelete();
        return redirect()->route('filmes.index')->with('success', 'Filme deletado permanentemente com sucesso!');
    }
    public function edit(Request $request, $id){
        $filme = Filme::findOrFail($id);
        if ($filme->user_id !== auth()->id()) { abort(403, 'Acesso não autorizado'); }

        $request->validate([
            'nome' => 'required|max:30',
            'sinopse' => 'required',
            'ano' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'categoria' => 'required|max:255',
            'trailer' => 'nullable|url|max:2000',
            'capa' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $filme->nome = $request->input('nome');
        $filme->sinopse = $request->input('sinopse');
        $filme->ano = $request->input('ano');
        $filme->categoria = $request->input('categoria');
        $filme->trailer = $request->input('trailer');

        if ($request->hasFile('capa')) {
            if ($filme->capa) {
                Storage::disk('public')->delete($filme->capa);
            }
            $capaPath = $request->file('capa')->store('capas', 'public');
            $filme->capa = $capaPath;
        }

        $filme->save();

        return redirect()->route('filmes.index')->with('success', 'Filme atualizado com sucesso!');
    }

    public function show($id){
        $filme = Filme::findOrFail($id);
        return view('filmes.show', compact('filme'));
    }
    public function restore($id)
    {
        $filme = Filme::onlyTrashed()->findOrFail($id);
        if ($filme->user_id !== auth()->id()) {
            abort(403, 'Acesso não autorizado');
        }
        $filme->restore();
        return redirect()->route('filmes.trash')->with('success', 'Filme restaurado com sucesso!');
    }
    public function search(Request $request){
        $filmes = Filme::query()
        ->when($request->input('user_id'), function ($q, $uid) {
            $q->where('user_id', $uid);
        })
        ->when($request->input('user_name'), function ($q, $userName) {
            $q->whereHas('user', function ($qUser) use ($userName) {
                $qUser->where('name', 'like', '%' . $userName . '%');
            });
        })->when($request->input('nome'), function ($q, $nome) {
            $q->where('nome', 'like', '%' . $nome . '%');
        })
        ->when($request->input('categoria'), function ($q, $categoria) {
            $q->where('categoria', 'like', '%' . $categoria . '%');
        })
        ->when($request->input('ano'), function ($q, $ano) {
            $q->where('ano', 'like', '%' . $ano . '%');
        })
        ->when($request->input('sinopse'), function ($q, $sinopse) {
            $q->where('sinopse', 'like', '%' . $sinopse . '%');
        })
        ->latest()
        ->get();

        return view('filmes.index', compact('filmes'));
    }
}