<?php

namespace App\Command;

use App\Service\FetchFruitsService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FetchFruitsCommand extends Command
{
    protected static $defaultName = 'app:fetch:fruits';
    protected static $defaultDescription = 'Fetch fruits and save to db';

    private $fetchFruitsService;

    public function __construct(
        FetchFruitsService $fetchFruitsService,
        LoggerInterface $logger
    ) {
        $this->fetchFruitsService = $fetchFruitsService;
        $this->logger = $logger;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->fetchFruitsService->fetch();

            $io->success(sprintf(
                'Successfully inserted data [%s]',
                $this->fetchFruitsService->getSuccessfulInsert()
            ));
        } catch (\Exception $e) {
            $io->error('Something went wrong. Please try again');
            $this->logger->error($e->getMessage());
        }

        return Command::SUCCESS;
    }
}
