<?php

namespace App\Providers\Filament;

use App\Filament\Pages\ManageSetting;
use App\Filament\Resources\ClientResource;
use App\Filament\Resources\FileDataResource;
use App\Filament\Resources\MultiDatabaseResource;
use App\Filament\Resources\RoleResource;
use App\Filament\Resources\UserResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Router;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;


class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->items([
                    ...Pages\Dashboard::getNavigationItems(),
                    NavigationItem::make()
                    ->label('Cài đặt')
                    ->icon('heroicon-o-cog')
                    ->url(ManageSetting::getUrl())
                    ->isActiveWhen(fn () => Route::is('filament.admin.pages.settings.*')),
                    NavigationItem::make()
                    ->label('Driver')
                    ->icon('heroicon-s-circle-stack')
                    ->url(route('files.index'))
                    ->isActiveWhen(fn () => Route::is('filament.admin.pages.settings.*')),
                    ...ClientResource::getNavigationItems(),
                    ...UserResource::getNavigationItems(),
                    ...FileDataResource::getNavigationItems(),
                    ...MultiDatabaseResource::getNavigationItems(),
                    ...RoleResource::getNavigationItems()
                ]);
            })
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
            ]);
    }
}
