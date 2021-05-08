<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * - конвертация видео.
 * - обрезка видео до 15 сек для бесплатной версии.
 * - создание обложек.
 */
class ProcessVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const COVER_SECONDS = [10, 11, 12, 13];

    private const COMPRESSED_DIMENSION = [576, 1024];

    private const FREE_SECONDS = 14;

    /**
     * @var string
     */
    private string $filename;

    /**
     * @var User
     */
    private User $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(UploadedFile $file, User $user)
    {
        $this->filename = $file->store('', 'local_video');
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $original = Video::create([
            'filename' => $this->filename,
            'user_id' => $this->user->id,
        ]);
        $original->loadToDisk();
        $original->compress()
            ->makeFreeVersion()
            ->makeCovers();

        // TODO: отдать видео по вебсокету
    }
}
