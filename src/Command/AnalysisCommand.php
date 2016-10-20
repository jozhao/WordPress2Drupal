<?php

/**
 * @file Analysis WordPress.
 */

namespace WordPress2Drupal\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Style\SymfonyStyle;
use WordPress2Drupal\Document\Document;
use WordPress2Drupal\Document\File;

/**
 * Class Analysis
 * @package WordPress2Drupal\Console.
 */
class AnalysisCommand extends Command
{
    /**
     * Command configure.
     */
    protected function configure()
    {
        $this
            ->setName('WordPress2Drupal:analysis')
            ->setDescription('Analysis the exported WordPress XML file')
            ->setHelp('This command helps you to analysis the exported WordPress XML file');

        $this
            ->setDefinition(
                new InputDefinition(
                    array(
                        new InputOption(
                            'file',
                            'f',
                            InputOption::VALUE_REQUIRED,
                            'File path of the exported WordPress XML file'
                        ),
                    )
                )
            );
    }

    /**
     * Execute the command.
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Analysis the exported WordPress XML');

        // Section - fetch XML information.
        $io->section('Fetch XML file information');
        $file_path = $input->getOption('file');

        if (!file_exists($file_path)) {
            throw new \InvalidArgumentException('File does NOT exist!');
        }

        // Bootstrap file.
        $file = new File($file_path);

        $io->table(
            array('File name', 'Mime type', 'File size', 'File path'),
            array(
                array(
                    $file->getFilename(),
                    $file->getFilemime(),
                    number_format($file->getSize() / 1048576, 2).' MB',
                    $file->getFilepath(),
                ),
            )
        );

        $io->newLine();

        // Send a warning if the file size is big.
        if ($file->getSize() >= 104857600) {
            $io->warning('The XML file size is over 100MB which will effect the running of migration');
        }

        // Section - parse XML.
        $io->section('Prepare new XML file for migration');
    }
}
