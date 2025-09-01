<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicationCategory extends Model
{
    use HasFactory;
    protected $table = 'publications_categories';

    protected $fillable = [
        'publication_id',
        'category_id',
    ];

    /**
     * Relación con publicaciones
     */
    public function publication()
    {
        return $this->belongsTo(Publication::class);
    }

    /**
     * Relación con categorías
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
