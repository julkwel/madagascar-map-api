<?php

namespace App\Command;

use App\Manager\ImportDataManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'import:data',
    description: 'Add a short description for your command',
)]
class ImportPayloadCommand extends Command
{
    public function __construct(private ImportDataManager $dataManager)
    {
        parent::__construct();
        $this->addOption('province', null, InputOption::VALUE_NONE);
        $this->addOption('code-postale', null, InputOption::VALUE_NONE);
    }

    protected function configure(): void
    {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ioStyle = new SymfonyStyle($input, $output);

        if ($input->getOption('code-postale')) {
            $this->dataManager->importCodePostale($ioStyle);
            exit(Command::SUCCESS);
        }

        if ($input->getOption('province')) {
            $this->dataManager->importProvince($ioStyle);
            exit(Command::SUCCESS);
        }

        $this->dataManager->importData($ioStyle);

        return Command::SUCCESS;
    }
}
