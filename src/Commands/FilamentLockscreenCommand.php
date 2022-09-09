<?php

namespace filament-lockscreen\FilamentLockscreen\Commands;

use Illuminate\Console\Command;

class FilamentLockscreenCommand extends Command
{
    public $signature = 'filament-lockscreen';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
