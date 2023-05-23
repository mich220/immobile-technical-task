<?php

declare(strict_types=1);

namespace App\Command;

use App\Tests\Generator\CategoryGenerator;
use App\Tests\Generator\ProductGenerator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-dummy-data',
    description: 'Creates dummy data. See README_TASK.md for more details.',
)]
class CreateDummyDataCommand extends Command
{
    public function __construct(
        private readonly ProductGenerator $productGenerator,
        private readonly CategoryGenerator $categoryGenerator
    ) {
        parent::__construct(null);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $categoryIds = $this->categoryGenerator->generate(10);
        $this->productGenerator->generate(100, $categoryIds);

        return Command::SUCCESS;
    }
}
