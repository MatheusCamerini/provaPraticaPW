<?php

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Filme;

class FilmeController extends Controller{
    public function index(){
        $filmes = auth()->user()->filmes;
        return view('filmes.index', compact('filmes'));
    }
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
        $filme->delete();
        return redirect()->route('dashboard')->with('success', 'Filme deletado com sucesso!');
    }
    public function edit(Request $request, $id){
        $filme = Filme::findOrFail($id);

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


}