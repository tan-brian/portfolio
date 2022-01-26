<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ville extends Model
{
    use HasFactory;

    public static function selectVille() {
        return Ville::select()
            ->orderby('nom')
            ->get();
    }
}
