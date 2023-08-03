<?php

declare (strict_types=1);

namespace App\UserInterface\Console\Command;

use App\Application\CommissionCalculationService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'commissions:calculate',
    description: 'Calculate batch of transaction commissions taken from the input file',
    hidden: false
)]
class CalculateCommissionsCommand extends Command
{
    public function __construct(private CommissionCalculationService $commissionCalcualtionService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('file', InputArgument::REQUIRED, 'Input file with transactions data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $commissions = $this->commissionCalcualtionService->calculateFromFile((string) $input->getArgument('file'));

        foreach ($commissions as $commission)
        {
            $output->writeln((string) round($commission, 2));
        }

        return Command::SUCCESS;
    }
}
