<?php

namespace App\Models;

use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Изображение.
 *
 * @property int $id
 * @property string $filename
 * @property int $user_id
 * @property-read string $url
 * @property-read Model|Eloquent $imageable
 * @method static Builder|Image newModelQuery()
 * @method static Builder|Image newQuery()
 * @method static Builder|Image query()
 * @method static Builder|Image whereFilename($value)
 * @method static Builder|Image whereId($value)
 * @method static Builder|Image whereImageableId($value)
 * @method static Builder|Image whereImageableType($value)
 * @method static Builder|Image whereUserId($value)
 * @mixin Eloquent
 */
class Image extends Model
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
     * @var string[]
     */
    protected $hidden = ['user_id', 'filename', 'pivot'];

    /**
     * @var string[]
     */
    protected $appends = ['url'];

    /**
     * @throws Exception
     */
    public function getUrlAttribute(): string
    {
        $expired = now()->addMinutes(config("filesystems.$this->disk.lifetime"));

        return cache()->remember(
            $this->filename,
            $expired,
            fn () => Storage::disk('s3_hot')->temporaryUrl($this->filename, $expired)
        );
    }
}
