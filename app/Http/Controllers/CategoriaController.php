<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
//para validação do nome das tags

use App\Models\Categoria;


class CategoriaController extends Controller
{

    public function index()
    {
        $categorias = Categoria::where('user_id', auth()->id())->get();
        return view('categorias.index', compact('categorias'));
    }

    /*======== Método create ========*/
    public function create()
    {
        return view('categorias.create');
    }

    /*======== Método store ========*/
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:categorias,nome,NULL,id,user_id,' . auth()->id(),

        ]);

        Categoria::create([
            'nome' => $validated['nome'],
            'user_id' => auth()->id()
        ]);

        return redirect()->route('categorias.index')->with('success', 'Categoria criada com sucesso!');
    }

    /*======== Método show ========*/
    public function show(Categoria $categoria) // Adicionar o método show
    {
        return view('categorias.show', compact('categoria'));
    }

    /*======== Método edit ========*/
    public function edit(Categoria $categoria)
    {
        if ($categoria->is_default) {
            return redirect()->route('categorias.index')->with('error', 'Não é possível editar a categoria padrão.');
        }
        return view('categorias.edit', compact('categoria'));
    }

    /*======== Método update ========*/
    public function update(Request $request, Categoria $categoria)
    {
        if ($categoria->is_default) {
            return redirect()->route('categorias.index')->with('error', 'Não é possível editar a categoria padrão.');
        }

        //Validação dos dados para não haver duplicatas
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:categorias,nome,' . $categoria->id . ',id,user_id,' . auth()->id(),
        ]);

        // Atualiza a categoria
        $categoria->update([
            'nome' => $validated['nome']]);

        return redirect()->route('categorias.index')
            ->with('success', 'Categoria atualizada com sucesso!');
    }

    /*======== Método destroy ========*/
    public function destroy(Categoria $categoria)
    {
        if ($categoria->is_default) {
            return redirect()->route('categorias.index')->with('error', 'Não é possível excluir a categoria padrão.');
        }

        $categoria->delete();
        return redirect()->route('categorias.index')->with('success', 'Categoria excluída com sucesso!');
    }
}
