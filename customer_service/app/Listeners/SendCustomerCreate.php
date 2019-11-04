<?php

namespace App\Listeners;

use App\Events\CustomerCreate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Artisan;

class SendCustomerCreate implements ShouldQueue
{
    /**
     * The name of the connection the job should be sent to.
     *
     * @var string|null
     */
    public $connection = 'database';

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'listeners';

    /**
     * The time (seconds) before the job should be processed.
     *
     * @var int
     */
    public $delay = 10;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CustomerCreate  $event
     * @return void
     */
    public function handle(CustomerCreate $event)
    {
        $customer = $event->customer();

        Artisan::call('kafka:producer', [
            'topic' => 'customers',
            'message' => json_encode($customer)
        ]);

        logger('CreateCustomer', [$customer]);
    }
}
