<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule; //para validação do nome das tags
use App\Models\Tag;


class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::where('user_id', auth()->id())->get();
        return view('tags.index', compact('tags'));
    }

    /*======== Método create ========*/
    public function create()
    {
        return view('tags.create');
    }

    /*======== Método store ========*/
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:tags,nome,NULL,id,user_id,' . auth()->id(),
        ]);

        Tag::create([
            'nome' => $validated['nome'],
            'user_id' => auth()->id()
        ]);
        // enviando msg junto com o redirecionamente
        return redirect()->route('tags.index')->with('success', 'Tag criada com sucesso!');
    }

    /*======== Método edit ========*/
    public function edit(Tag $tag)
    {
        $notas = $tag->notas ?? [];// Recupera as notas que pertencem a essa tag
        return view('tags.edit', compact('tag'));
    }

    /*======== Método update ========*/
    public function update(Request $request, Tag $tag)
    {
        //Validação para não haver duplicatas
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:tags,nome,' . $tag->id . ',id,user_id,' . auth()->id(),

        ]);

        $tag->update([
            'nome' => $validated['nome']

        ]);

        return redirect()->route('tags.index')->with('success', 'Tag atualizada com sucesso!');
    }

    /*======== Método destroy ========*/
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('tags.index')->with('success', 'Tag excluída com sucesso!');
    }
}
