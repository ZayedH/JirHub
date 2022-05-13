<?php

namespace App\Command;

use App\Handler\HerokuHandler;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class HerokuManageCommand extends Command
{
    /** @var LoggerInterface */
    protected $logger;

    /** @var HerokuHandler */
    private $herokuHandler;

    /** @var string */
    protected static $defaultName = 'heroku:manage:dynos';

    public function __construct(LoggerInterface $logger, HerokuHandler $herokuHandler)
    {
        parent::__construct();

        $this->logger        = $logger;
        $this->herokuHandler = $herokuHandler;
    }

    protected function configure()
    {
        $this->setDescription('Put down all specified heroku dynos (set quantity to 0)');

        $this
            ->addOption(
                'action',
                null,
                InputOption::VALUE_REQUIRED,
                'Action to perform on dynos.'
            )
            ->addOption(
                'apps',
                null,
                InputOption::VALUE_REQUIRED,
                'The Heroku app names that should be put down. List separated by comma.'
            )
            ->addOption(
                'types',
                null,
                InputOption::VALUE_REQUIRED,
                'The dyno types that should be put down. List separated by comma.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->logger->info(sprintf('%s : exécution de la commande', self::$defaultName));

        $action    = $input->getOption('action');
        $appNames  = explode(',', $input->getOption('apps'));
        $dynoTypes = explode(',', $input->getOption('types'));

        switch ($action) {
            case 'up':
                $this->herokuHandler->updateDynoQuantity($appNames, $dynoTypes, 1);

                break;

            case 'down':
                $this->herokuHandler->updateDynoQuantity($appNames, $dynoTypes, 0);

                break;
        }

        return 0;
    }
}
