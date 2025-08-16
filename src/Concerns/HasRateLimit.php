<?php

namespace lockscreen\FilamentLockscreen\Concerns;

use lockscreen\FilamentLockscreen\Lockscreen;

trait HasRateLimit
{
    protected bool $enableRateLimit = true;

    protected int $rateLimit = 5;

    protected int $rateLimitDecayMinutes = 5;

    protected bool $forceLogout = false;

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
     * Disable Rate Limit functionality
     *
     * @return HasLockscreenConfiguration|Lockscreen
     */
    public function disableRateLimit(): self
    {
        $this->enableRateLimit = false;
    }

    public function isRateLimitEnabled(): bool
    {
        return $this->enableRateLimit;
    }

    public function isForceLogout(): bool
    {
        return $this->forceLogout;
    }

    public function getRateLimitDecayMinutes(): int
    {
        return $this->rateLimitDecayMinutes;
    }

    public function getRateLimitLimit(): int
    {
        return $this->rateLimit;
    }
}
