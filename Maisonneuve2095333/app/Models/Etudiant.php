<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'nom', 'adresse', 'phone', 'email', 'date_naissance', 'ville_id'];

    public function ville()
    {
        return $this->belongsTo(Ville::class);
    }
    
    public static function selectEtudiant() {
      
        return Etudiant::select()
                ->join('users', 'users.id', '=','etudiants.id')
                ->where('is_admin', '=', null)
                ->get(); 
    }
   
}


