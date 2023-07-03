<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int              $id
 * @property string           $o_number
 * @property string           $name
 * @property string           $type
 * @property string           $address
 * @property string           $building
 * @property string           $room
 * @property string           $exact_position
 * @property string           $info
 * @property string           $contact
 *
 * @property int              $identity_id
 * @property Staff|Customer   $identity
 * @property int              $asset_id
 * @property Asset            $asset
 * @property int              $deployed_slot_id
 * @property DeploymentSlot   $deployedSlot
 *
 * @property DeploymentSlot[] $deploymentSlots
 * @property ProductSlot[]    $productSlots
 */
/* updated_revision
import_revision
deleted_revision
option1
option2
option3
*/

class Deployment extends Base
{
    protected $appends = ['identity_slug'];

    /**
     * @return Staff|Customer|MorphTo
     */
    public function identity(): MorphTo
    {
        return $this->morphTo('identity');
    }

    /**
     * @return Asset|BelongsTo
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id', 'id');
    }

    public function getIdentitySlugAttribute(): string
    {
        return $this->identity instanceof Customer ? 'customer' : 'staff';
    }

    /**
     * @return DeploymentSlot|BelongsTo
     */
    public function deployedSlot(): BelongsTo
    {
        return $this->belongsTo(DeploymentSlot::class, 'deployed_slot_id', 'id');
    }

    /**
     * @return DeploymentSlot[]|HasMany
     */
    public function deploymentSlots(): HasMany
    {
        return $this->hasMany(DeploymentSlot::class, 'deployment_id', 'id');
    }
}
