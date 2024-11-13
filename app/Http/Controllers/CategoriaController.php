<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;


class CategoriaController extends Controller
{

    public function index()
    {
        $categorias = Categoria::where('user_id', auth()->id())->get();
        return view('categorias.index', compact('categorias'));
    }
    public function create()
    {
        return view('categorias.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255'
        ]);

        Categoria::create([
            'nome' => $validated['nome'],
            'user_id' => auth()->id()
        ]);

        return redirect()->route('categorias.index');
    }
    public function show(Categoria $categoria) // Adicionar o método show
    {
        return view('categorias.show', compact('categoria'));
    }
    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', compact('categoria'));
    }
    public function update(Request $request, Categoria $categoria)
    {
        // Validação dos dados
        $validated = $request->validate([
            'nome' => 'required|string|max:255'
        ]);

        // Atualiza a categoria
        $categoria->nome = $validated['nome'];
        $categoria->save();

        return redirect()->route('categorias.index')
            ->with('success', 'Categoria atualizada com sucesso!');
    }
    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return redirect()->route('categorias.index')->with('success', 'Categoria excluída com sucesso!');
    }

}
