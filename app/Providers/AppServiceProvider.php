<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\User;
use Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Gate::define('view-order', function (User $user, Order $order) {
            return $user->id === $order->user_id || $user->isBoard();
        });

        Gate::define('admin-pending-orders', function (User $user) {
            return $user->isEmployee() || $user->isBoard();
        });

        Gate::define('admin-cancel-orders', function (User $user) {
            return $user->isBoard();
        });

        Gate::define('admin-complete-orders', function (User $user) {
            return $user->isBoard();
        });

        Gate::define('admin-stock', function (User $user) {
            return $user->isEmployee() || $user->isBoard();
        });

        Gate::define('admin-supply', function (User $user) {
            return $user->isEmployee() || $user->isBoard();
        });

        Gate::define('admin-settings', function (User $user) {
            return $user->isBoard();
        });

        Gate::define('manage-product', function (User $user) {
            return $user->isBoard();
        });

        Gate::define('manage-category', function (User $user) {
            return $user->isBoard();
        });

        Gate::define('cart', function (User $user) {
            return !$user->isEmployee();
        });

        Gate::define('admin-dash', function (User $user) {
            return $user->isBoard();
        });

        Gate::define('dash', function (User $user) {
            return !$user->isEmployee();
        });

        Gate::define('management', function (User $user) {
            return $user->isEmployee() || $user->isBoard();
        });

        Gate::define('admin-users', function (User $user) {
            return $user->isBoard();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

