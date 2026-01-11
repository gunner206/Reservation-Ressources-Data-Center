<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    // Nom de la table en MAJUSCULES
    protected $table = 'RESOURCES';

    protected $fillable = [
        'name', 'code', 'category_id', 'manager_id',
        'description', 'specs', 'is_active'
    ];

    protected $casts = [
        'specs' => 'array',
        'is_active' => 'boolean'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class, 'resource_id');
    }
}