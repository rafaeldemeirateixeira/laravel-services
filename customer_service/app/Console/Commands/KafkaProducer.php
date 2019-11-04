<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Enqueue\RdKafka\RdKafkaConnectionFactory;

class KafkaProducer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kafka:producer {topic} {message}';

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
        $message = $this->argument('message');

        $connectionFactory = new RdKafkaConnectionFactory([
            'global' => [
                'group.id' => uniqid('', true),
                'metadata.broker.list' => 'kafka:9092',
                'enable.auto.commit' => 'false',
            ],
            'topic' => [
                'auto.offset.reset' => 'beginning',
            ],
        ]);
        
        $context = $connectionFactory->createContext();

        $message = $context->createMessage($message);
        $fooTopic = $context->createTopic($topic);

        $context->createProducer()->send($fooTopic, $message);
    }
}
