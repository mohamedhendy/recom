<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int      $id
 * @property string   $document_able_type
 * @property int      $document_able_id
 * @property int      $document_id
 * @property Document $document
 * @property string   $payload
 * @property string   $type
 */
class HasDocument extends Base
{
    /**
     * @return Document|BelongsTo
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id', 'id');
    }

    /**
     * @return MorphTo
     */
    public function document_able(): MorphTo
    {
        return $this->morphTo("document_able");
    }
}
