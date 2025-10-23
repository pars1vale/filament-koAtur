<?php

namespace App\Providers\Filament;

use App\Models\Outlet;
use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
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
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
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

            // Ordering Navigation Group
            ->navigationGroups([
                'Products',
                'Stock Adjustments',
                'Quotations',
                'Purchases',
                'Purchases Return',
                'Sales',
                'Sales Return',
                'Parties',
                'Expenses',
                'Settings',
                'User Management'
            ])
            // Products Resource Path
            ->discoverResources(in: app_path('Filament/Resources/Products'), for: 'App\\Filament\\Resources\\Products')

            // Purchases Resource Path
            ->discoverResources(in: app_path('Filament/Resources/Purchases'), for: 'App\\Filament\\Resources\\Purchases')

            // Parties Resource Path
            ->discoverResources(in: app_path('Filament/Resources/Parties'), for: 'App\\Filament\\Resources\\Parties')

            // Expense Resource Path
            ->discoverResources(in: app_path('Filament/Resources/Expenses'), for: 'App\\Filament\\Resources\\Expenses')

            // Settings Resource Path
            ->discoverResources(in: app_path('Filament/Resources/Settings'), for: 'App\\Filament\\Resources\\Settings')

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,

                // Custom Page for System Settings
                \App\Filament\Pages\Settings\SystemSettings::class,
                // Custom Page Stock Adjustment
                \App\Filament\Pages\AdjustStock::class

            ])


            ->navigationItems([
                NavigationItem::make('Create Expense')
                    ->url(fn() => \App\Filament\Resources\Expenses\ExpenseResource::getUrl('create'))
                    ->icon('heroicon-o-plus-circle')
                    ->group('Expenses')
                    ->sort(2)
                    ->visible(fn() => Auth::user()->can('create_expenses::expense')),
                NavigationItem::make('Create Purchases')
                    ->url(fn() => \App\Filament\Resources\Purchases\PurchaseResource::getUrl('create'))
                    ->icon('heroicon-o-plus-circle')
                    ->group('Purchases')
                    ->sort(1)
                    ->visible(fn() => Auth::user()->can('create_purchases::purchase')),

                NavigationItem::make('Create Purchase Returns')
                    ->url(fn() => \App\Filament\Resources\Purchases\PurchaseReturnResource::getUrl('create'))
                    ->icon('heroicon-o-plus-circle')
                    ->group('Purchases Return')
                    ->sort(1)
                    ->visible(fn() => Auth::user()->can('create_purchases::purchase::return')),

                NavigationItem::make('Create Sales')
                    ->url(fn() => \App\Filament\Resources\Sales\SaleResource::getUrl('create'))
                    ->icon('heroicon-o-plus-circle')
                    ->group('Sales')
                    ->sort(1)
                    ->visible(fn() => Auth::user()->can('create_sales::sale')),

                NavigationItem::make('Create Sales Return')
                    ->url(fn() => \App\Filament\Resources\Sales\SaleReturnResource::getUrl('create'))
                    ->icon('heroicon-o-plus-circle')
                    ->group('Sales Return')
                    ->sort(1)
                    ->visible(fn() => Auth::user()->can('create_sales::sale::return')),
            ])
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
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
            ])
            ->tenant(Outlet::class);
    }
}
