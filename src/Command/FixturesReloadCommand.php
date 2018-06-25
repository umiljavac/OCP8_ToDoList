<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 27/03/2018
 * Time: 17:08
 *
 * By Karolos Lykos
 */

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class FixturesReloadCommand
 *
 * Used to reload the database and fill it with data fixtures
 */
class FixturesReloadCommand extends Command
{
    /**
     * Configure
     */
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:fixturesReload')

            // the short description shown while running "php bin/console list"
            ->setDescription('Drop/Create Database and load Fixtures ....')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(
                'This command allows you to load dummy data by 
                recreating database and loading fixtures...'
            );
    }

    /**
     * Execute
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     *
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $application = $this->getApplication();
        $application->setAutoExit(false);

        $output->writeln(
            [
            '===================================================',
            '*********        Dropping DataBase        *********',
            '===================================================',
            '',
            ]
        );

        $options = array(
            'command' => 'doctrine:database:drop',
            "--force" => true,
        );
        $application->run(
            new \Symfony\Component\Console\Input\ArrayInput($options)
        );


        $output->writeln(
            [
            '===================================================',
            '*********        Creating DataBase        *********',
            '===================================================',
            '',
            ]
        );

        $options = array(
            'command' => 'doctrine:database:create',
            "--if-not-exists" => true,
            );
        $application->run(
            new \Symfony\Component\Console\Input\ArrayInput($options)
        );

        $output->writeln(
            [
            '===================================================',
            '*********         Updating Schema         *********',
            '===================================================',
            '',
            ]
        );

        //Create de Schema
        $options = array(
            'command' => 'doctrine:schema:update',
            "--force" => true,
        );
        $application->run(
            new \Symfony\Component\Console\Input\ArrayInput($options)
        );

        $output->writeln(
            [
            '===================================================',
            '*********          Load Fixtures          *********',
            '===================================================',
            '',
            ]
        );

        //Loading Fixtures
        $options = array(
            'command' => 'doctrine:fixtures:load',
            "--no-interaction" => true,
            );
        $application->run(
            new \Symfony\Component\Console\Input\ArrayInput($options)
        );
    }
}
