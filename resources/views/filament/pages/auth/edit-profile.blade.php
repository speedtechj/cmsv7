<div>
    <header class="fi-header-heading py-8">
        <h1 class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">
            Your Profile</h1>
        <p class="fi-header-heading  tracking-tight text-gray-950 dark:text-white" >
            Manage your profile</p>
        </p>
    </header>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}

        <x-filament-panels::form.actions :actions="$this->getCachedFormActions()" 
        :full-width="$this->hasFullWidthFormActions()" 
       />
    </x-filament-panels::form>
    <div>
