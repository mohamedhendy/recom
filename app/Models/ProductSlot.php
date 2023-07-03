<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int              $id
 * @property string           $name
 * @property int              $number
 * @property int              $product_id
 * @property Product          $product
 * @property                  $default_info
 *
 * @property DeploymentSlot[] $deploymentSlots
 */
class ProductSlot extends Base
{
    /**
     * @return Product|BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * @return DeploymentSlot[]|HasMany
     */
    public function deploymentSlots(): HasMany
    {
        return $this->hasMany(DeploymentSlot::class, 'product_slot_id', 'id');
    }
}
