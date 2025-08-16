<?php

namespace lockscreen\FilamentLockscreen\Concerns;

use lockscreen\FilamentLockscreen\Lockscreen;

trait HasSessionLockScreenUrl
{
    protected string $url = '/screen/lock';

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
}
