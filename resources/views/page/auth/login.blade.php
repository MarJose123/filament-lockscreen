<div>
    <x-filament-panels::page.simple>
        @props([
            'after' => null,
            'heading' => null,
            'subheading' => null,
        ])

        <main>
            {{--Slot --}}
            <div class="flex flex-row justify-center">
                <img class="w-56 h-56 rounded-full" src="{{ \Filament\Facades\Filament::getUserAvatarUrl(\Filament\Facades\Filament::auth()->user())}}" alt="avatar">
            </div>
            <div class="flex flex-row justify-center">
                <div class="font-medium dark:text-white">
                    <div>{{\Filament\Facades\Filament::auth()->user()?->name ?? ''}}</div>
                </div>
            </div>

            <x-filament-panels::form wire:submit="authenticate">
                {{ $this->form }}

                <x-filament-panels::form.actions
                    :actions="$this->getCachedFormActions()"
                    :full-width="$this->hasFullWidthFormActions()"
                />
            </x-filament-panels::form>
            <div class="text-center pt-4">
                <a class="text-primary-600"
                   href="#!"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{ __('filament-lockscreen::default.button.switch_account') }}
                </a>
                <form id="logout-form" action="{{ url(filament()->getLogoutUrl()) }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>


        </main>
    </x-filament-panels::page.simple>
</div>

