<?php


namespace App\Models;


use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin Builder
 *
 * @property int           $created_by_id
 * @property User          $createdBy
 * @property int           $updated_by_id
 * @property User          $updatedBy
 * @property DateTime      $created_at
 * @property DateTime      $updated_at
 * @property DateTime      $deleted_at
 * @property HasDocument[] $documents
 */
class Base extends Model
{
    use HasFactory, SoftDeletes;

    protected $hidden = ['updated_at', 'deleted_at'];

    protected $guarded = [];

    /**
     * @return User|BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }

    /**
     * @return User|BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_id', 'id');
    }

    /**
     * @param $value
     * @return string
     */
    public function getCreatedAtAttribute($value): string
    {
        return Carbon::parse($value)->toDateTimeString();
    }

    /**
     * @return Document[]|MorphMany
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(HasDocument::class, 'document_able');
    }
}
