<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::macro('whereLike', function (string $column, string $search) {
            return $this->where($column, 'LIKE', '%' . $search . '%');
        });

        Builder::macro('orWhereLike', function (string $column, string $search) {
            return $this->orWhere($column, 'LIKE', '%' . $search . '%');
        });
    }
}
