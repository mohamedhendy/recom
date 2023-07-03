<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

/**
 * @property int           $id
 * @property string        $name
 * @property string        $original_name
 * @property string        $mime_type
 * @property int           $size
 * @property string        $path
 * @property string        $description
 *
 * @property hasDocument[] $hasDocuments
 */
class Document extends Base
{
    protected $appends = ['url'];

    /**
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return Storage::url($this->path);
    }

    /**
     * @return hasDocument[]|HasMany
     */
    public function hasDocuments(): HasMany
    {
        return $this->hasMany(hasDocument::class, 'document_id', 'id');
    }
}
