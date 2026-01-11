<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /**
     * Nom de la table en base de données
     * @var string
     */
    protected $table = 'CATEGORIES';

    /**
     * Colonnes qui peuvent être remplies en masse
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'icon',
    ];

    /**
     * Relation avec les ressources
     */
    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class, 'category_id');
    }
}