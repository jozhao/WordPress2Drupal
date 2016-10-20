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

        // Start the document.
        $document = new Document($file->getFilepath());

        // Section - parse XML.
        $this->parse($document, $io);

        // Display the error or success message.
        $errors = $document->getErrors();
        if ($errors) {
            $io->error($errors);
        } else {
            $io->success('All done! cheers');
        }

        $io->newLine();
    }

    /**
     * Parse XML file.
     * @param Document $document
     * @param $io
     */
    protected function parse(Document $document, $io)
    {
        // Load document into memory.
        $qp = $document->load();
        try {
            $siteTitle = $qp->xpath('/rss/channel/title')->text();
            // Fetch users.
            $users = $qp->xpath('/rss/channel/wp:author');
            $sizeOfUsers = $users->count();
            // Fetch categories.
            $categories = $qp->xpath('/rss/channel/wp:category');
            $sizeOfCategories = $categories->count();
            // Fetch tags.
            $tags = $qp->xpath('/rss/channel/wp:tag');
            $sizeOfTags = $tags->count();
            // Fetch terms.
            $terms = $qp->xpath('/rss/channel/wp:term');
            $sizeOfTerms = $terms->count();
            // Fetch items.
            $items = $qp->top('item');
            $sizeOfItems = $items->count();
            // Fetch attachments.
            $attachments = $qp->xpath('/rss/channel/item/wp:attachment_url');
            $sizeOfAttachments = $attachments->count();

            // Section - parse XML.
            $io->section('Fetch WordPress site information');

            $io->table(
                array('Site name', 'Users', 'Categories', 'Tags', 'Terms', 'Items', 'Attachments'),
                array(
                    array(
                        $siteTitle,
                        $sizeOfUsers,
                        $sizeOfCategories,
                        $sizeOfTags,
                        $sizeOfTerms,
                        $sizeOfItems,
                        $sizeOfAttachments,
                    ),
                )
            );

            $io->newLine();

            // Section - parse XML.
            $io->section('Fetch WordPress site users information');

            if ($sizeOfUsers > 0) {
                $userArray = [];
                foreach ($users as $user) {
                    $userArray[] = array(
                        $user->xpath('wp:author_login')->text(),
                        $user->xpath('wp:author_email')->text(),
                        $user->xpath('wp:author_display_name')->text(),
                        $user->xpath('wp:author_id')->text(),
                    );
                }
                asort($userArray);
                $io->table(
                    array('Username', 'User email', 'Full name', 'User ID in WordPress'),
                    $userArray
                );
            }

            $io->newLine();

            // Section - parse XML.
            $io->section('Fetch WordPress site categories information');

            if ($sizeOfCategories > 0) {
                $categoryArray = [];
                foreach ($categories as $category) {
                    $categoryArray[] = array(
                        $category->xpath('wp:cat_name')->text(),
                        $category->xpath('wp:category_nicename')->text(),
                        $category->xpath('wp:category_parent')->text(),
                        $category->xpath('wp:term_id')->text(),
                    );
                }
                asort($categoryArray);
                $io->table(
                    array('Category', 'Slug', 'Parent', 'Internal ID in WordPress'),
                    $categoryArray
                );
            }

            $io->newLine();

            // Section - parse XML.
            $io->section('Fetch WordPress site tags information');

            if ($sizeOfTags > 0) {
                $tagArray = [];
                foreach ($tags as $tag) {
                    $tagArray[] = array(
                        $tag->xpath('wp:tag_name')->text(),
                        $tag->xpath('wp:tag_slug')->text(),
                        $tag->xpath('wp:term_id')->text(),
                    );
                }
                asort($tagArray);
                $io->table(
                    array('Tag', 'Slug', 'Internal ID in WordPress'),
                    $tagArray
                );
            }

            $io->newLine();

            // Section - parse XML.
            $io->section('Fetch WordPress site terms information');

            if ($sizeOfTerms > 0) {
                $termArray = [];
                foreach ($terms as $term) {
                    $termArray[] = array(
                        $term->xpath('wp:term_taxonomy')->text(),
                        $term->xpath('wp:term_name')->text(),
                        $term->xpath('wp:term_slug')->text(),
                        $term->xpath('wp:term_parent')->text(),
                        $tag->xpath('wp:term_id')->text(),
                    );
                }
                asort($termArray);
                $io->table(
                    array('Taxonomy', 'Term', 'Slug', 'Parent', 'Internal ID in WordPress'),
                    $termArray
                );
            }

            $io->newLine();
        } catch (\Exception $exception) {
            $document->addError('Cannot parse XML file content');
        }
    }
}
