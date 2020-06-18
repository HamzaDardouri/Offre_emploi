<?php

namespace App\Command;

use App\Service\CategoryService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateCategoryCommand extends Command
{
    /** @var CategoryService */
    private $categoryService;

    /**
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:create-category')

            // the short description shown while running "php bin/console list"
            ->setDescription('Crée une nouvelle catégorie.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Cette commande vous permet d ajouter une nouvelle catégorie dans db ...')

            // configure an argument
            ->addArgument('name', InputArgument::REQUIRED, 'Le nom de la catégorie.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('name')) {
            $question = new Question('<question>Veuillez choisir un nom: </question>');
            $question->setValidator(function ($name) {
                if (empty($name)) {
                    throw new \Exception('Le nom ne peut pas être vide');
                }

                return $name;
            });

            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument('name', $answer);
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Category Creator',
            '============',
            '',
        ]);

        // retrieve the argument value using getArgument()
        $output->writeln(sprintf('Name: %s', $input->getArgument('name')));

        $this->categoryService->create($input->getArgument('name'));

        $output->writeln('<fg=green>Catégorie créée avec succès!</>');
    }
}
