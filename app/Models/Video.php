<?php

namespace App\Models;

use Eloquent;
use App\Traits\HasCovers;
use App\Traits\BelongToUser;
use App\Traits\VideoConversation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Видео.
 *
 * @property int $id
 * @property string $filename
 * @property string $modification
 * @property-read Post|null $post
 * @property int|null $original_id
 * @property int $user_id
 * @property-read Collection|Video[] $modifications
 * @property-read int|null $modifications_count
 * @property-read Video|null $originalVideo
 * @property-read User $user
 * @property-read Collection|Image[] $covers
 * @property-read int|null $covers_count
 * @property-read string $disk
 * @property-read string $url
 * @method static Builder|Video newModelQuery()
 * @method static Builder|Video newQuery()
 * @method static Builder|Video query()
 * @method static Builder|Video whereModification($value)
 * @method static Builder|Video whereFilename($value)
 * @method static Builder|Video whereId($value)
 * @method static Builder|Video whereOriginalId($value)
 * @method static Builder|Video whereUserId($value)
 * @mixin Eloquent
 */
class Video extends Model
{
    use HasFactory, HasCovers, VideoConversation, BelongToUser;

    public const MODIFICATION_ORIGINAL = 'original';

    public const MODIFICATION_FREE = 'free';

    public const MODIFICATION_COMPRESSED = 'compressed';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $guarded = ['id'];

    /**
     * @var string[]
     */
    protected $attributes = [
        'modification' => self::MODIFICATION_ORIGINAL,
        'original_id' => null,
    ];

    /**
     * @var string[]
     */
    protected $hidden = ['filename', 'modification', 'original_id', 'user_id', 'covers'];

    /**
     * Пост
     *
     * @return HasOne
     */
    public function post(): HasOne
    {
        return $this->hasOne(Post::class);
    }

    /**
     * Оригинал.
     *
     * @return BelongsTo
     */
    public function originalVideo(): BelongsTo
    {
        return $this->belongsTo(static::class, 'original_id', 'id');
    }

    /**
     * Модификации.
     *
     * @return HasMany
     */
    public function modifications(): HasMany
    {
        return $this->hasMany(static::class, 'original_id', 'id');
    }

    /**
     * @return Model|null
     */
    public function compressed(): ?self
    {
        return $this->modifications()
            ->where('modification', static::MODIFICATION_COMPRESSED)
            ->first();
    }

    /**
     * @return Model|null
     */
    public function free(): ?self
    {
        return $this->modifications()
            ->where('modification', static::MODIFICATION_FREE)
            ->first();
    }
}
