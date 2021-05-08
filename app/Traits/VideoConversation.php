<?php

namespace App\Traits;

use Exception;
use RuntimeException;
use Illuminate\Http\File;
use Illuminate\Support\Str;
use FFMpeg\Format\Video\X264;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Filters\Video\VideoFilters;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\MediaOpener;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

trait VideoConversation
{
    private static array $COVER_SECONDS = [10, 11, 12, 13];

    private static array $COMPRESSED_DIMENSION = [576, 1024];

    private static int $FREE_SECONDS = 14;

    /**
     * Загрузка файла в контейнер
     *
     * @param bool $removeLocal
     *
     * @return bool
     */
    public function loadToDisk(bool $removeLocal = false): bool
    {
        if ($this->existsLocalFile()) {
            $path = Storage::disk('local_video')->path($this->filename);
            Storage::disk($this->disk)->putFileAs('', new File($path), $this->filename);

            if ($removeLocal) {
                $this->removeLocalFile();
            }

            return true;
        }

        return false;
    }

    /**
     * Ссылка на файл.
     *
     * @throws Exception
     *
     * @return string
     */
    public function getUrlAttribute(): string
    {
        $expired = now()->addMinutes(config("filesystems.$this->disk.lifetime"));

        return cache()->remember(
            $this->filename,
            $expired,
            fn () => Storage::disk($this->disk)->temporaryUrl($this->filename, $expired)
        );
    }

    /**
     * Хранилище.
     *
     * @return string
     */
    public function getDiskAttribute(): string
    {
        return $this->modification === static::MODIFICATION_ORIGINAL
            ? 's3_cold'
            : 's3_hot';
    }

    /**
     * @return bool
     */
    public function existsLocalFile(): bool
    {
        return Storage::disk('local_video')->exists($this->filename);
    }

    /**
     * @return MediaOpener
     */
    public function getMediaOpener(): MediaOpener
    {
        return $this->existsLocalFile()
            ? FFMpeg::fromDisk('local_video')->open($this->filename)
            : FFMpeg::openUrl($this->url, []);
    }

    /**
     * @return bool
     */
    private function removeLocalFile(): bool
    {
        if ($this->existsLocalFile()) {
            return Storage::disk('local_video')->delete($this->filename);
        }

        return false;
    }

    /**
     * Сжатие оригинального видео.
     *
     * @return $this
     */
    public function compress(): self
    {
        if ($this->modification !== static::MODIFICATION_ORIGINAL) {
            throw new RuntimeException(trans('errors.video.not_original'));
        }

        $filename = Str::uuid()->toString().'.mp4';
        $this->getMediaOpener()
            ->export()
            ->toDisk('s3_hot')
            ->inFormat(new X264)
            ->addFilter(function (VideoFilters $filters) {
                $filters->resize(new Dimension(...static::$COMPRESSED_DIMENSION));
            })
            ->save($filename);

        $this->removeLocalFile();

        return static::create([
            'filename' => $filename,
            'user_id' => $this->user_id,
            'original_id' => $this->id,
            'modification' => static::MODIFICATION_COMPRESSED,
        ]);
    }

    /**
     * Создает бесплатную версию видео.
     *
     * @return $this
     */
    public function makeFreeVersion(): self
    {
        if ($this->modification !== static::MODIFICATION_COMPRESSED) {
            throw new RuntimeException(trans('errors.video.not_compressed'));
        }

        $filename = Str::uuid()->toString().'.mp4';
        $this->getMediaOpener()
            ->export()
            ->toDisk('s3_hot')
            ->inFormat(new X264)
            ->addFilter(function (VideoFilters $filters) {
                $filters->clip(
                    TimeCode::fromSeconds(0),
                    TimeCode::fromSeconds(static::$FREE_SECONDS)
                );
            })
            ->save($filename)
            ->getVideoStream();

        $this->removeLocalFile();

        return static::create([
            'filename' => $filename,
            'user_id' => $this->user_id,
            'original_id' => $this->original_id,
            'modification' => static::MODIFICATION_FREE,
        ]);
    }

    /**
     * Создание обложек.
     *
     * @return array
     */
    public function makeCovers(): array
    {
        $covers = [];

        $this->getMediaOpener()
            ->each(
                static::$COVER_SECONDS,
                function ($ffmpeg, $seconds, $key) use (&$covers) {
                    $filename = Str::uuid()->toString().'.jpg';
                    $ffmpeg->getFrameFromSeconds($seconds)
                        ->export()
                        ->toDisk('s3_hot')
                        ->save($filename);
                    $covers[] = [
                        'filename' => $filename,
                        'user_id' => $this->user_id,
                    ];
                }
            );

        $this->removeLocalFile();

        return $this->modification === static::MODIFICATION_ORIGINAL
            ? $this->covers()->createMany($covers)
            : $this->originalVideo->covers()->createMany($covers);
    }
}
