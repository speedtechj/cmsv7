<x-filament-panels::page>
    <form wire:submit="search">
        {{ $this->form }}
        <button type="submit">Search</button>
    </form>
    <div>
        {{ $this->table }}
    </div>
</x-filament-panels::page>
