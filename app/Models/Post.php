<?php

namespace App\Models;

use Eloquent;
use App\Traits\HasTags;
use App\Traits\HasCovers;
use App\Traits\BelongToUser;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Пост.
 *
 * @property int $id
 * @property string $title
 * @property string $hashtag
 * @property bool $is_commentable
 * @property int $gender
 * @property bool $is_paid
 * @property float $amount
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Tag[] $tags
 * @property-read int|null $tags_count
 * @property-read Video $video
 * @property int $video_id
 * @property-read Collection|Image[] $covers
 * @property-read int|null $covers_count
 * @property int $shows
 * @property-read User $user
 * @method static Builder|Post newModelQuery()
 * @method static Builder|Post newQuery()
 * @method static Builder|Post query()
 * @method static Builder|Post whereCreatedAt($value)
 * @method static Builder|Post whereGender($value)
 * @method static Builder|Post whereHashtag($value)
 * @method static Builder|Post whereId($value)
 * @method static Builder|Post whereIsCommentable($value)
 * @method static Builder|Post whereIsPaid($value)
 * @method static Builder|Post whereAmount($value)
 * @method static Builder|Post whereTitle($value)
 * @method static Builder|Post whereUpdatedAt($value)
 * @method static Builder|Post whereUserId($value)
 * @method static Builder|Post whereVideoId($value)
 * @method static Builder|Post whereShows($value)
 * @mixin Eloquent
 */
class Post extends Model
{
    use HasFactory, HasCovers, BelongToUser, HasTags;

    /**
     * @var string[]
     */
    protected $guarded = ['id'];

    /**
     * @var string[]
     */
    protected $casts = [
        'is_commentable' => 'bool',
        'is_paid' => 'bool',
        'video_id' => 'integer',
        'gender' => 'integer',
    ];

    /**
     * Видео.
     *
     * @return BelongsTo
     */
    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }
}
