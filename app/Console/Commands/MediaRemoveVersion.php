<?php

namespace App\Console\Commands;

use App\Jobs\RenameFile;
use App\Model\Admin\MediaFile;
use App\Services\Media\MediaFileService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class MediaRemoveVersion extends Command
{
    protected $signature = 'media:version_remove';
    protected $description = 'Command description';

    const VERSION_TO_DELETE = 'instructors-small';
    const VERSION_REPLACE_TO = 'users';

    public function handle(MediaFileService $service)
    {
        $this->info('Start delete ' . self::VERSION_TO_DELETE);
        $total = 0;
        MediaFile::where('version', self::VERSION_TO_DELETE)
            ->chunkById(100, function (Collection $list) use ($service, &$total) {
                $this->info('found - ' . $list->count());
                foreach ($list as $item) {
                    /* @type MediaFile $item */
                    $service->deleteVersion($item, self::VERSION_REPLACE_TO);
                    $total++;
                }
                $this->info('processed - ' . $total);
            });
        $this->info('finished');
    }
}
