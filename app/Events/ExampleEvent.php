<?php

namespace App\Events;

use Illuminate\Support\Facades\Log;

class ExampleEvent extends Event
{

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
        Log::info( __CLASS__ );
    }
}
