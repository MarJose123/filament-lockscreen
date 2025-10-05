<?php

namespace lockscreen\FilamentLockscreen\Concerns;

use Closure;
use Filament\Support\Concerns\EvaluatesClosures;

trait CanDisplayName
{
    use EvaluatesClosures;

    protected string $displayName = 'name';

    protected bool $enableDisplayName = true;

    public function displayName(): string
    {
        return $this->displayName;
    }

    public function disableDisplayName(Closure|bool $disable = true): static
    {
        $this->enableDisplayName = ! $this->evaluate($disable);

        return $this;
    }

    public function isDisplayNameEnabled(): bool
    {
        return $this->enableDisplayName;
    }

    /**
     * @param  Closure|string  $attribute  The attribute of the User Model you want to display. By default, it is the 'name' attribute
     * @return $this
     */
    public function displayNameUsing(Closure|string $attribute): static
    {
        $this->displayName = $this->evaluate($attribute);

        return $this;
    }
}
