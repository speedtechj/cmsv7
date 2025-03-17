<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use App\Models\Companyinfo;
use Filament\PanelProvider;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use App\Filament\Pages\Auth\EditProfile;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use Rmsramos\Activitylog\ActivitylogPlugin;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->spa()
            ->login()
            ->databaseNotifications()
            ->maxContentWidth(MaxWidth::Full)
            ->brandName('Forex CMSv4')
            ->profile(EditProfile::class)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
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
            ->userMenuItems([
                'logout' => MenuItem::make()
                ->url('/logout'),
                MenuItem::make()
                ->label('Dashboard')
                ->icon('heroicon-o-user')
                ->url('/'),
                MenuItem::make()
                ->label('1224')
                ->icon('heroicon-o-user')
                ->url('/1224'),
                MenuItem::make()
                ->label('Shipping Monitoring')
                ->icon('heroicon-o-computer-desktop')
                ->url('/monitoring'),
                // MenuItem::make()
                // ->label('WSM Version 1.0')
                // ->icon('heroicon-o-computer-desktop')
                // ->url(function (){
                //     $test = Companyinfo::all()->first();
                //    return 'https://forexskidding.test/';
                // })
            ])
            ->navigationGroups( [
                NavigationGroup::make( 'Batch Status' )->icon('heroicon-o-document-check'),
               
                
            ] )
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                ActivitylogPlugin::make()
                ->label('AuditLog')
                    ->pluralLabel(' Audit Logs'),
            ]);
           
    }
}
