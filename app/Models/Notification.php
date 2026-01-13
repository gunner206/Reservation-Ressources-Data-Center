<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    // Liste des colonnes modifiables (vérifie tes colonnes dans la migration)
    protected $fillable = [
        'user_id',
        'type',
        'message',
        'is_read',
        'data'
    ];

    // Liaison : Une notification appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}