<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ['titre_fr', 'titre_en', 'contenu_fr', 'contenu_en', 'etudiant_id'];
    public $timestamps = true;
    
    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    public static function selectArticle() {
      
        $lg= "_en";
        if(session()->has('locale') && session()->get('locale') == 'fr'){
          $lg = "_fr";
        }
  
        $query = Article::Select('id',
          DB::raw('(case when titre'.$lg.' is null then titre_en else titre'.$lg.' end) as titre'),
          DB::raw('(case when contenu'.$lg.' is null then contenu_en else contenu'.$lg.' end) as contenu'),
          'etudiant_id',
          'updated_at'
        )
        ->OrderBy('updated_at','DESC')
        ->get();
        return $query;
      }
}
