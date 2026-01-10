<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ressource extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'description', 'categorie_id',
        'cpu', 'ram', 'stockage', 'os', 'statut', 'responsable_id'
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class, 'ressource_id');
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function scopeDisponible($query)
    {
        return $query->where('statut', 'actif');
    }
    
    public function scopeEnMaintenance($query)
    {
        return $query->where('statut', 'maintenance');
    }
    
    public function estDisponible()
    {
        return $this->statut === 'actif';
    }
}