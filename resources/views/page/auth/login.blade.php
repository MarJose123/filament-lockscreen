<x-filament-panels::page.simple>

    <div class="flex flex-row justify-center">
        <img class="w-56 h-56 rounded-full" src="{{ \Filament\Facades\Filament::getUserAvatarUrl(\Filament\Facades\Filament::auth()->user())}}" alt="avatar">
    </div>

    <x-filament-panels::form wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>
    <div class="text-center">
        <x-filament::link>
            <a class="text-primary-600 hover:text-primary-700"
               href="#!"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                {{ __('filament-lockscreen::default.button.switch_account') }}</a>
        </x-filament::link>
        <form id="logout-form" action="{{ url(filament()->getLogoutUrl()) }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </div>

</x-filament-panels::page.simple>
