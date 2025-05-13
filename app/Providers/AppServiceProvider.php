<?php

namespace App\Providers;

use App\Models\Aplikasi;
use App\Models\Artikel;
use App\Models\Event;
use App\Models\Sekolah;
use App\Models\TahunPelajaran;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

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
        view()->composer('*', function ($view) {
            $view->with('aplikasi', Aplikasi::first());
            $view->with('tapel', TahunPelajaran::aktif()->first());
            $view->with('sekolah', Sekolah::first());
        });
    }
}
