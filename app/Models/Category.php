<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int        $id
 * @property string     $name
 * @property string     $payload
 * @property int        $parent_category_id
 * @property Category   $parentCategory
 *
 * @property Category[] $subCategories
 * @property Product[]  $products
 * @property Product[]  $subcategoryProducts
 */
class Category extends Base
{
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('orderBy', function (Builder $builder) {
            $builder->orderBy('name');
        });
    }


    /**
     * @return Category|BelongsTo
     */
    public function parentCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_category_id', 'id');
    }

    /**
     * @return Category[]|HasMany
     */
    public function subCategories(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_category_id', 'id');
    }

    /**
     * @return Product[]|HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    /**
     * @return Product[]|HasMany
     */
    public function subcategoryProducts(): HasMany
    {
        return $this->hasMany(Product::class, 'subcategory_id', 'id');
    }
}
