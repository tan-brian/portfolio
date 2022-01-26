<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $articles = Article::selectArticle(); 
        return view('forum.index', [
            'articles' => $articles  
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
        $request->validate([
            'titre_fr' => 'required|max:50',
            'titre_en' => 'required|max:50',
            'contenu_en' =>  'required',
            'contenu_fr' =>  'required',
          ]);

        Article::create([
            'titre_fr' => $request->titre_fr,
            'titre_en' => $request->titre_en,
            'contenu_fr' =>  $request->contenu_fr,
            'contenu_en' =>  $request->contenu_en,
            'etudiant_id' =>  Auth::user()->id
        ]);

        return redirect('/forum');
    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        if(Auth::user()->id != $article->etudiant_id && Auth::user()->is_admin != 1){
            return redirect('etudiants');
        }

        return view('forum.edit', [
            'article' => $article,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        if(Auth::user()->id != $article->etudiant_id && Auth::user()->is_admin != 1) {
            return redirect('etudiants');
        }

        $request->validate([
            'titre_fr' => 'required|max:50',
            'titre_en' => 'required|max:50',
            'contenu_en' =>  'required',
            'contenu_fr' =>  'required',
          ]);
        
          Article::find($article->id)
          ->fill($request->all())
          ->save();

          return redirect("forum");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        if(Auth::user()->id != $article->etudiant_id && Auth::user()->is_admin != 1) {
            return redirect('etudiants');
        }
        
        $article->delete();
        return redirect('forum');
    }
}
