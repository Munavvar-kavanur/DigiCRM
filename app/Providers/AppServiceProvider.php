<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Share settings with all views
        try {
            $settings = \App\Models\Setting::getAll();
            view()->share('globalSettings', $settings);
        
            \App\Models\Invoice::observe(\App\Observers\InvoiceObserver::class);
            \App\Models\Project::observe(\App\Observers\ProjectObserver::class);
            \App\Models\Estimate::observe(\App\Observers\EstimateObserver::class);
            \App\Models\Payroll::observe(\App\Observers\PayrollObserver::class);
            \App\Models\Expense::observe(\App\Observers\ExpenseObserver::class);

            // View Composer for Topbar Notifications
            view()->composer('layouts.topbar', function ($view) {
                if (\Illuminate\Support\Facades\Auth::check()) {
                    $userId = \Illuminate\Support\Facades\Auth::id();
                    $unreadNotificationsCount = \App\Models\Reminder::where('user_id', $userId)
                        ->where('status', 'pending')
                        ->where('reminder_date', '<=', now())
                        ->count();
                    
                    $latestNotifications = \App\Models\Reminder::where('user_id', $userId)
                        ->where('status', 'pending')
                        ->where('reminder_date', '<=', now())
                        ->orderBy('reminder_date', 'asc') // Oldest due first (most urgent)
                        ->take(5)
                        ->get();

                    $view->with('unreadNotificationsCount', $unreadNotificationsCount);
                    $view->with('latestNotifications', $latestNotifications);
                } else {
                    $view->with('unreadNotificationsCount', 0);
                    $view->with('latestNotifications', collect());
                }
            });

        } catch (\Exception $e) {
            // Database might not be ready yet (e.g. during composer install)
        }
    }
}
