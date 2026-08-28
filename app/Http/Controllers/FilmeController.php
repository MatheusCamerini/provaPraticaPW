<?php

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Filme;
use Illuminate\Support\Facades\Storage;
class FilmeController extends Controller{
    public function create(Request $request){
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

        return redirect()->route('dashboard')->with('success', 'Filme criado com sucesso!');
    }
    public function destroy($id){
        $filme = Filme::findOrFail($id);
        if ($filme->user_id !== auth()->id()) { abort(403, 'Acesso não autorizado'); }
        $filme->delete();
        return redirect()->route('dashboard')->with('success', 'Filme deletado com sucesso!');
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

        return redirect()->route('dashboard')->with('success', 'Filme atualizado com sucesso!');
    }

    public function show($id){
        $filme = Filme::findOrFail($id);
        return view('filmes.show', compact('filme'));
    }

    public function index(){
        $filmes = Filme::all();
        return view('filmes.index', compact('filmes'));
    }
    public function search(Request $request){
        $query = $request->input('query');
        $filmes = Filme::where('nome', 'like', '%' . $query . '%')->get();
        return view('filmes.index', compact('filmes'));
    }
}