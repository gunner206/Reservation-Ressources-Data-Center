<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    // Relation : Une catégorie a plusieurs ressources
    public function ressources()
    {
        return $this->hasMany(Ressource::class, 'categorie_id');
    }
}