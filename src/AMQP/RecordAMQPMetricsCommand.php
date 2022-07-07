<?php

namespace App\AMQP;

use App\AMQP\Repository\AMQPQueueMetricsRepository;
use Elasticsearch\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RecordAMQPMetricsCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'record:amqp-metrics';

    private AMQPQueueMetricsRepository $AMQPQueueMetricsRepository;
    private Client $elasticsearchClient;
    private string $index;

    public function __construct(AMQPQueueMetricsRepository $AMQPQueueMetricsRepository, Client $elasticsearchClient, string $index)
    {
        parent::__construct();

        $this->AMQPQueueMetricsRepository = $AMQPQueueMetricsRepository;
        $this->elasticsearchClient        = $elasticsearchClient;
        $this->index                      = $index;
    }

    protected function configure()
    {
        $this->setDescription('Record all AMQP queues metrics');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $params  = ['body' => []];
        $metrics = $this->AMQPQueueMetricsRepository->getQueuesMetrics();
        $now     = (new \DateTimeImmutable())->format(\DateTimeInterface::RFC3339);

        foreach ($metrics as $metric) {
            $params['body'][] = [
                'index' => [
                    '_index'  => $this->index,
                    'op_type' => 'create',
                ],
            ];

            $metric['@timestamp'] = $now;
            $params['body'][]     = $metric;
        }

        $this->elasticsearchClient->bulk($params);

        return 0;
    }
}
