<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int            $id
 * @property int            $first_deployment_slot_id
 * @property DeploymentSlot $firstDeploymentSlot
 * @property int            $second_deployment_slot_id
 * @property DeploymentSlot $secondDeploymentSlot
 */
class DeploymentSlotConnection extends Base
{
    /**
     * @return DeploymentSlot|BelongsTo
     */
    public function firstDeploymentSlot(): BelongsTo
    {
        return $this->belongsTo(DeploymentSlot::class, 'id', 'first_deployment_slot_id');
    }

    /**
     * @return DeploymentSlot|BelongsTo
     */
    public function secondDeploymentSlot(): BelongsTo
    {
        return $this->belongsTo(DeploymentSlot::class, 'id', 'second_deployment_slot_id');
    }
}
