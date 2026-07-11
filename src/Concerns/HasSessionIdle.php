<?php

namespace lockscreen\FilamentLockscreen\Concerns;

use lockscreen\FilamentLockscreen\Lockscreen;

trait HasSessionIdle
{
    protected bool $enableActivityTimeout = false;

    protected int $activityTimeout = 1800;

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

    /**
     * Disable Idle Timeout
     *
     * @return HasSessionIdle|Lockscreen
     */
    public function disableIdleTimeout(): self
    {
        $this->enableActivityTimeout = false;
    }

    /**
     * Check if Idle Timeout is enabled
     */
    public function isEnableIdleTimeout(): bool
    {
        return $this->enableActivityTimeout;
    }

    /**
     * @return int Idle Timeout in Seconds
     */
    public function getIdleTimeout(): int
    {
        return $this->activityTimeout;
    }
}
