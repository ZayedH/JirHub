<?php

namespace App\OutdatedLibraries\OutdatedLibrariesMarkdown;

use App\OutdatedLibraries\OutdatedFileToTable\OutdatedFileToTable;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AndroidOutdated extends Command
{
    use PatternTrait;
    /** @var string */
    protected static $defaultName = 'collect:android-outdated-libraries';
    private OutdatedFileToTable $OutdatedFileToTable;

    public function __construct(OutdatedFileToTable $OutdatedFileToTable)
    {
        parent::__construct();
        $this->OutdatedFileToTable = $OutdatedFileToTable;
    }

    protected function configure()
    {
        $this->setDescription('android collectting outdated libraries ');
        $this->addArgument('path', InputArgument::REQUIRED, 'a path to your txt file is required');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getArgument('path');
        $tab  = $this->OutdatedFileToTable->androidOutdatedTable($path);

        foreach ($tab as $key => $value) {
            $tab[$key] = $this->patternLigne($value);
        }
        $output->writeln(array_merge($this->generateHeader('chronos (android)'), $tab));

        return 0;
    }
}
