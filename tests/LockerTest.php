<?php

use Livewire\Mechanisms\HandleRequests\HandleRequests;
use lockscreen\FilamentLockscreen\Tests\Fixtures\SecretPage;
use lockscreen\FilamentLockscreen\Tests\Fixtures\User;

beforeEach(function (): void {
    SecretPage::$revealed = 0;

    $this->actingAs(User::query()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]));
});

/**
 * Render the secret page and return its Livewire snapshot, as an open browser tab holds it.
 */
function secretPageSnapshot(): string
{
    $html = test()->get('/admin/secret')->assertOk()->getContent();

    preg_match_all('/wire:snapshot="([^"]+)"/', $html, $matches);

    foreach ($matches[1] as $snapshot) {
        $snapshot = html_entity_decode($snapshot, ENT_QUOTES);

        if (array_key_exists('isSecretPage', json_decode($snapshot, true)['data'] ?? [])) {
            return $snapshot;
        }
    }

    throw new RuntimeException('No snapshot of the secret page found.');
}

/**
 * Call reveal() on the secret page through the Livewire update endpoint, like the browser does.
 */
function callReveal(string $snapshot)
{
    return test()
        ->withHeaders(['X-Livewire' => '1'])
        ->postJson(app(HandleRequests::class)->getUpdateUri(), [
            'components' => [[
                'snapshot' => $snapshot,
                'updates' => new stdClass,
                'calls' => [['method' => 'reveal', 'params' => []]],
            ]],
        ]);
}

it('redirects a page visit to the lockscreen while locked', function (): void {
    $this->withSession(['lockscreen' => true])
        ->get('/admin/secret')
        ->assertRedirect(route('lockscreen.admin.page'));
});

it('does not let the X-Livewire header skip the lockscreen', function (): void {
    $this->withSession(['lockscreen' => true])
        ->withHeaders(['X-Livewire' => '1'])
        ->get('/admin/secret')
        ->assertRedirect(route('lockscreen.admin.page'));
});

it('runs Livewire actions while unlocked', function (): void {
    callReveal(secretPageSnapshot())->assertOk();

    expect(SecretPage::$revealed)->toBe(1);
});

it('blocks Livewire actions of an already open page while locked', function (): void {
    $snapshot = secretPageSnapshot();

    $this->withSession(['lockscreen' => true]);

    callReveal($snapshot)->assertRedirect(route('lockscreen.admin.page'));

    expect(SecretPage::$revealed)->toBe(0);
});
