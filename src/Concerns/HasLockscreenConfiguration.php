<?php

namespace lockscreen\FilamentLockscreen\Concerns;

use Filament\Support\Concerns\HasIcon;
use lockscreen\FilamentLockscreen\Lockscreen;
use lockscreen\FilamentLockscreen\Traits\HasRateLimit;
use lockscreen\FilamentLockscreen\Traits\HasSessionIdle;
use lockscreen\FilamentLockscreen\Traits\HasSessionLockScreenUrl;

trait HasLockscreenConfiguration
{
    protected string $emailColumnName = 'email';

    protected string $passwordColumnName = 'password';

    use HasIcon;
    use HasRateLimit;
    use HasSessionIdle;
    use HasSessionLockScreenUrl;

    /**
     * @param  string  $emailColumnName  By default, it will be using the 'email' column of the User Table
     * @param  string  $passwordColumnName  By default, it will be using the 'password' column of the User Table
     * @return HasLockscreenConfiguration|Lockscreen
     */
    public function usingCustomTableColumns(string $emailColumnName, string $passwordColumnName): self
    {
        $this->emailColumnName = $emailColumnName;
        $this->passwordColumnName = $passwordColumnName;

        return $this;
    }

    public function getCustomTableColumns(): array
    {
        return [
            $this->emailColumnName,
            $this->passwordColumnName,
        ];
    }
}
