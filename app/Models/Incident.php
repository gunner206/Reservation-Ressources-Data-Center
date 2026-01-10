<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre', 'description', 'ressource_id', 'utilisateur_id',
        'statut', 'priorite', 'date_resolution', 'solution'
    ];

    // Relation : Un incident concerne une ressource
    public function ressource()
    {
        return $this->belongsTo(Ressource::class, 'ressource_id');
    }
}