<?php

namespace App\Listeners;

use App\Events\JurnalMengajarBelumDibuat;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class KirimNotifikasiJurnal
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(JurnalMengajarBelumDibuat $event): void
    {
        //
    }
}
