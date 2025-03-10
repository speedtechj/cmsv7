<x-filament-panels::page>
    <form wire:submit="search" wire:keydown.enter="search">
        {{ $this->form }}
    </form>
    <div>
        {{ $this->table }}
    </div>
</x-filament-panels::page>
