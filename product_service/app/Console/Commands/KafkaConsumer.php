<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Enqueue\RdKafka\RdKafkaConnectionFactory;
use App\Models\Customer;

class KafkaConsumer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kafka:consumer {topic}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $topic = $this->argument('topic');

        $connectionFactory = new RdKafkaConnectionFactory([
            'global' => [
                'group.id' => 'laravel',
                'metadata.broker.list' => 'kafka:9092',
                'enable.auto.commit' => 'true',
            ],
            'topic' => [
                'auto.offset.reset' => 'beginning',
            ],
        ]);

        $context = $connectionFactory->createContext();
        $fooQueue = $context->createQueue($topic);
        $consumer = $context->createConsumer($fooQueue);

        try {
            while (true) {
                $message = $consumer->receive()
                    ->getBody();

                $obj = json_decode($message);

                $customer = Customer::create([
                    'id' => $obj->id,
                    'name' => $obj->name
                ]);

                dump($customer);
            }

            $consumer->acknowledge($message);
        } catch (\Exception $e) {
            $this->info($e->getMessage());
            $consumer->reject($message);
        }
    }
}
