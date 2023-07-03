<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int              $id
 * @property string           $info
 * @property int              $deployment_id
 * @property Deployment       $deployment
 * @property int              $product_slot_id
 * @property ProductSlot      $productSlot
 *
 * @property Deployment[]     $childDeployments
 * @property DeploymentSlot[] $localConnections
 * @property DeploymentSlot[] $remoteConnections
 */
class DeploymentSlot extends Base
{
    /**
     * @return Deployment|BelongsTo
     */
    public function deployment(): BelongsTo
    {
        return $this->belongsTo(Deployment::class, 'deployment_id', 'id');
    }

    /**
     * @return ProductSlot|BelongsTo
     */
    public function productSlot(): BelongsTo
    {
        return $this->belongsTo(ProductSlot::class, 'product_slot_id', 'id');
    }

    /**
     * @return Deployment[]|HasMany
     */
    public function childDeployments(): HasMany
    {
        return $this->hasMany(Deployment::class, 'deployed_slot_id', 'id');
    }

    /**
     * @return DeploymentSlot[]|BelongsToMany
     */
    public function localConnections()
    {
        return $this->belongsToMany(
            DeploymentSlot::class,
            DeploymentSlotConnection::class,
            'first_deployment_slot_id',
            'second_deployment_slot_id',
            'id',
            'id')
            ->withPivot('id')
            ->whereNull('deployment_slot_connections.deleted_at')
            ->withTimestamps();
    }

    /**
     * @return DeploymentSlot[]|BelongsToMany
     */
    public function remoteConnections()
    {
        return $this->belongsToMany(
            DeploymentSlot::class,
            DeploymentSlotConnection::class,
            'second_deployment_slot_id',
            'first_deployment_slot_id',
            'id',
            'id')
            ->withPivot('id')
            ->whereNull('deployment_slot_connections.deleted_at')
            ->withTimestamps();
    }
}
