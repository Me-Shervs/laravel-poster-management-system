<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function posters()
    {
        return $this->belongsToMany(Poster::class, 'poster_category');
    }

    /*
    |--------------------------------------------------------------------------
    | Recursive Helpers
    |--------------------------------------------------------------------------
    */

    public static function getDescendantIds(
        int $categoryId
    ): array {

        $children = static::where('parent_id', $categoryId)->get();

        $ids = [];

        foreach ($children as $child) {

            $ids[] = $child->id;

            $ids = array_merge(
                $ids,
                static::getDescendantIds($child->id)
            );
        }

        return $ids;
    }
}