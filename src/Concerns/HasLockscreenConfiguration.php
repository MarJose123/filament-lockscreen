<?php

namespace lockscreen\FilamentLockscreen\Concerns;

use Closure;
use lockscreen\FilamentLockscreen\Lockscreen;

trait HasLockscreenConfiguration
{
    private bool $hasEnabledLockscreen = true;

    private string $icon = 'heroicon-o-lock-closed';

    private string $url = '/screen/lock';

    private string $emailColumnName = 'email';

    private string $passwordColumnName = 'password';

    private bool $enableRateLimit = true;

    private int $rateLimit = 5;

    private int $rateLimitDecayMinutes = 5;

    private bool $forceLogout = false;

    private bool $enableActivityTimeout = true;

    private int $activityTimeout;

    /**
     * @param  bool  $hasEnabledLockscreen
     * @return HasLockscreenConfiguration|Lockscreen
     */
    public function enable(bool|Closure $hasEnabledLockscreen = true): self
    {
        $this->hasEnabledLockscreen = (bool) $this->evaluate($hasEnabledLockscreen);

        return $this;
    }

    public function isEnable(): bool
    {
        return $this->hasEnabledLockscreen;
    }

    /**
     * @return HasLockscreenConfiguration|Lockscreen
     */
    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @param  string  $url  Don't provide '/' or empty as it will conflict with the main index
     * @return HasLockscreenConfiguration|Lockscreen
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

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

    /**
     * @param  int  $limit  How many times the user will allow retrying again after failure to login
     * @param  int  $decayMinutes  Minutes on how long the login page will be available again.
     * @param  bool  $forceLogout  If TRUE, the user will be redirected to the login page after the $limit that has been set is exhausted.
     * @return HasLockscreenConfiguration|Lockscreen
     */
    public function enableRateLimit(int $limit = 5, int $decayMinutes = 5, bool $forceLogout = false): self
    {
        $this->enableRateLimit = true;
        $this->rateLimit = $limit;
        $this->rateLimitDecayMinutes = $decayMinutes;
        $this->forceLogout = $forceLogout;

        return $this;
    }

    /**
     * @return HasLockscreenConfiguration|Lockscreen
     */
    public function disableRateLimit(): self
    {
        $this->enableRateLimit = false;
    }

    /**
     * @return array{enable: int, decay_minutes: int, force_logout: bool}
     */
    public function getRateLimit(): array
    {
        return [
            'enable' => $this->rateLimit,
            'decay_minutes' => $this->rateLimitDecayMinutes,
            'force_logout' => $this->forceLogout,
        ];
    }

    /**
     * @param  int  $seconds  The number of seconds of inactivity before the screen automatically locks. Defaults to 1800 (30 minutes)
     * @return HasLockscreenConfiguration|Lockscreen
     */
    public function enableIdleTimeout(int $seconds = 60 * 30): self
    {
        $this->enableActivityTimeout = true;
        $this->activityTimeout = $seconds;

        return $this;
    }

    public function disableIdleTimeout(): self
    {
        $this->enableActivityTimeout = false;
    }

    /**
     * @return array{enable: bool, minutes: int}
     */
    public function getIdle(): array
    {
        return [
            'enable' => $this->enableActivityTimeout,
            'minutes' => $this->activityTimeout ?? 30,
        ];
    }
}
