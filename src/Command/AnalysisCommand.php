<?php

/**
 * @file Analysis Wordpress.
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
                            'File path of the exported Wordpress XML file'
                        ),
                        new InputOption(
                            'save',
                            's',
                            InputOption::VALUE_OPTIONAL,
                            'Save the XML file for further usage'
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

        $io->title('Analysis the exported Wordpress XML');

        // Section - fetch XML information.
        $io->section('Fetch XML file information');
        $file_path = $input->getOption('file');

        if (!file_exists($file_path)) {
            throw new \InvalidArgumentException('File does NOT exist!');
        }

        // Build up file object.
        $file = new \stdClass();
        $file->filename = basename($file_path);
        $file->uri = $file_path;
        $file->filemime = mime_content_type($file_path);
        $file->filesize = @filesize($file_path);

        $table = new Table($output);
        $table
            ->setHeaders(array('File name', 'Mime type', 'File size', 'File path'))
            ->setRows(
                array(
                    array(
                        $file->filename,
                        $file->filemime,
                        number_format($file->filesize / 1048576, 2).' MB',
                        $file->uri,
                    ),
                )
            );
        $table->render();

        // Send a warning if the file size is big.
        if ($file->filesize >= 10737418) {
            $io->warning('The XML file size is over 100MB which will effect the running of migration process');
        }

        // Section - parse XML.
        $io->section('Prepare new XML file for migration');
    }
}
