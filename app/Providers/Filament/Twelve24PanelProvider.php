<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use App\Filament\Pages\Auth\EditProfile;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class Twelve24PanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('twelve24')
            ->path('1224')
            ->databaseNotifications()
            ->brandName('12:24 Cargo Express')
            ->spa()
            ->login()
            ->readOnlyRelationManagersOnResourceViewPagesByDefault(false)
            ->profile(EditProfile::class)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Twelve24/Resources'), for: 'App\\Filament\\Twelve24\\Resources')
            ->discoverPages(in: app_path('Filament/Twelve24/Pages'), for: 'App\\Filament\\Twelve24\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Twelve24/Widgets'), for: 'App\\Filament\\Twelve24\\Widgets')
            ->widgets([
                
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->userMenuItems( [
                MenuItem::make()
                ->label( 'Admin Acount' )
                ->icon( 'heroicon-o-user' )
                ->url( '/admin' )
                ->visible( fn (): bool => auth()->user()->isAdmin() )
            ] )
            ->navigationGroups( [
                NavigationGroup::make( 'Invoice Status' )->icon( 'heroicon-o-circle-stack'),
                // NavigationGroup::make( 'Philippines Location' )->icon( 'heroicon-o-map-pin' ),
                // NavigationGroup::make( 'App Settings')->icon( 'heroicon-o-cog-6-tooth' )
                
            ] );;
            
            
    }
}
