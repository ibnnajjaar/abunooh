<x-filament-panels::page>
    @if (auth()->user()->can('settings.update'))
        <form wire:submit="save">
            {{ $this->form }}

            <div class="mt-4 flex gap-3">
                @foreach ($this->getFormActions() as $action)
                    {{ $action }}
                @endforeach
            </div>
        </form>
    @else
        {{ $this->infolist }}
    @endif
</x-filament-panels::page>
