<?php

namespace lockscreen\FilamentLockscreen\Tests\Fixtures;

use Filament\Pages\Page;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;

/**
 * A panel page with one Livewire action, standing in for any page of a real panel.
 */
class SecretPage extends Page
{
    protected static ?string $slug = 'secret';

    /** Lets the tests find this component's snapshot in the rendered page. */
    public bool $isSecretPage = true;

    /** How many times reveal() ran in this process. */
    public static int $revealed = 0;

    public function reveal(): void
    {
        static::$revealed++;
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Text::make('secret page content'),
        ]);
    }
}
