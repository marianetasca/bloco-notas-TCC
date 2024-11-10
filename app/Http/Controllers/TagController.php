<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Categoria;


class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::all();
        return view('tags.index', compact('tags'));
    }

    public function create()
    {
        $categorias = Categoria::all(); // Carregar todas as categorias
        return view('tags.create', compact('categorias'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255'
        ]);

        Tag::create([
            'nome' => $validated['nome'],
            'user_id' => auth()->id()
        ]);

        return redirect()->route('tags.index')->with('success', 'Tag criada com sucesso!');
    }

    public function edit(Tag $tag)
    {
        return view('tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255'
        ]);

        $tag->update([
            'nome' => $validated['nome']
        ]);

        return redirect()->route('tags.index')->with('success', 'Tag atualizada com sucesso!');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('tags.index')->with('success', 'Tag excluída com sucesso!');
    }
}
