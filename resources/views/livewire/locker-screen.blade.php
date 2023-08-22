<div @class([
    'flex items-center justify-center min-h-screen bg-gray-100 text-gray-900 filament-login-page',
    'dark:bg-gray-900 dark:text-white' => config('filament.dark_mode'),
])>
    <div class="px-6 -mt-16 md:mt-0 md:px-2 max-w-md space-y-8 w-screen">
        <form wire:submit.prevent="login" @class([
            'p-8 space-y-8 bg-white/50 backdrop-blur-xl border border-gray-200 shadow-2xl rounded-2xl relative',
            'dark:bg-gray-900/50 dark:border-gray-700' => config('filament.dark_mode'),
        ])>
            <div>
                <h2 class="font-bold tracking-tight text-center text-2xl">
                    {{ __('filament-lockscreen::default.heading') }}
                </h2>
            </div>
            <div class="flex flex-row justify-center">
                <img class="w-56 h-56 rounded-full" src="{{ \Filament\Facades\Filament::getUserAvatarUrl(\Filament\Facades\Filament::auth()->user())}}" alt="avatar">
            </div>

            {{ $this->form }}

            <x-filament::button type="submit" class="w-full">
                {{ __('filament-lockscreen::default.button.submit_label') }}
            </x-filament::button>
        </form>
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
    </div>
    @livewire('notifications')
</div>
