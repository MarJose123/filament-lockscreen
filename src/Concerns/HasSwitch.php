<?php

namespace lockscreen\FilamentLockscreen\Concerns;

use Closure;
use Filament\Support\Concerns\EvaluatesClosures;

trait HasSwitch
{
    use EvaluatesClosures;

    protected bool $enablePlugin = false;

    /**
     *  Enable the plugin
     *
     * @return $this
     */
    public function enablePlugin(Closure|bool $enable = true): static
    {
        $enable = $this->evaluate($enable);
        $this->enablePlugin = $enable;

        return $this;
    }

    /**
     * Check if the plugin is enabled
     */
    public function isPluginEnabled(): bool
    {
        return $this->enablePlugin;
    }
}
