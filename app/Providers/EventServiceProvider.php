<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\JurnalMengajarBelumDibuat' => [
            'App\Listeners\KirimNotifikasiJurnal',
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot()
    {
        //
    }
}
