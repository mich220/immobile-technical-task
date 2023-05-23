<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Api\Register;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateApplicationCommand extends Command
{
    public function __construct(private readonly Register $register)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:create-application')
            ->setDescription('Create new key into user/api database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Application Key Creator',
            '================',
        ]);

        $helper = $this->getHelper('question');

        $question = new Question('Please enter public application name: ');
        $name = $helper->ask($input, $output, $question);

        $question = new Question('Please enter application key: ');
        $key = $helper->ask($input, $output, $question);

        $question = new Question('Please enter application secret (password): ');
        $secret = $helper->ask($input, $output, $question);

        $app = $this->register->createApplication($name, $secret, $key);
        if (null !== $app) {
            $output->writeln('<info>Application Key has been created.</info>');
        } else {
            $output->writeln("<error>Something went wrong. Application Key wasn't created!</error>");
        }

        return 0;
    }
}
