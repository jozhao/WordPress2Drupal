<?php

/**
 * @file Optimiz exported Wordpress XML.
 */

namespace Wordpress2Drupal\Command;

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
 * Class OptimizCommand.
 * @package Wordpress2Drupal\Command
 */
class OptimizCommand extends Command
{
    /**
     * Command configure.
     */
    protected function configure()
    {
        $this
            ->setName('wordpress2drupal:optimize')
            ->setDescription('Optimize the exported Wordpress XML file')
            ->setHelp('This command helps you to optimize the exported Wordpress XML file');

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
                            'Save the Wordpress exported XML file in a custom path for further usage'
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

        $io->title('Optimize the exported Wordpress XML');

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
        $io->newLine();

        // Section - parse XML.
        $io->section('Clean up XML file');
        try {
            $qp = qp($file->uri);
            print $qp->count();

        } catch (\Exception $exception) {
            $io->error('Cannot read XML file content');
        }

        // Section - parse XML.
        $io->section('Save the new XML file');
        try {
            $vendorDir = dirname(dirname(__FILE__));
            $baseDir = dirname($vendorDir);
            $current_folder = $baseDir.DIRECTORY_SEPARATOR;
            $directory = rtrim($current_folder.'data', '/\\');

            // Create directory if not exist.
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            // Set directory permission.
            if (!is_writable($directory)) {
                chmod($directory, 0755);
            } else {
                $filename = $directory.DIRECTORY_SEPARATOR.'optimized-'.time().'-'.$file->filename;
            }

            // Write XML.
            $qp->writeXML($filename);
        } catch (\Exception $exception) {
            $io->error('Cannot save optimized XML file content');
        }


        $io->success('All done! cheers');
        $io->newLine();
    }
}
