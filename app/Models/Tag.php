<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Тег.
 *
 * @property int $id
 * @property string $title
 * @property-read Collection|Post[] $posts
 * @property-read int|null $posts_count
 * @method static Builder|Tag newModelQuery()
 * @method static Builder|Tag newQuery()
 * @method static Builder|Tag query()
 * @method static Builder|Tag whereId($value)
 * @method static Builder|Tag whereTitle($value)
 * @mixin Eloquent
 */
class Tag extends Model
{
    use HasFactory;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $guarded = ['id'];

    /**
     * Посты.
     *
     * @return BelongsToMany
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }
}
