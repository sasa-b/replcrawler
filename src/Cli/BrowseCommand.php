<?php

declare(strict_types=1);

namespace Sco\REPLCrawler\Cli;

use Sco\REPLCrawler\Cli\Exception\InvalidActionChoice;
use Sco\REPLCrawler\Cli\Validator\UrlValidator;
use Sco\REPLCrawler\Crawler;
use Sco\REPLCrawler\Dom\Link;
use Sco\REPLCrawler\Website;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\HttpClient\HttpClient;

final class BrowseCommand extends Command
{
    public const NAME = 'browse';

    protected static $defaultName = self::NAME;

    private function getUrlArgument(InputInterface $input): string
    {
        return (new UrlValidator())($input->getArgument('url'));
    }

    private function isREPLMode(InputInterface $input): bool
    {
        return $input->getOption('repl') === true;
    }

    private function isPrintTreeMode(InputInterface $input): bool
    {
        return $input->hasOption('print-tree');
    }

    protected function configure(): void
    {
        $this->addArgument('url', InputArgument::REQUIRED);

        $this->addOption('headers', 'hs', InputOption::VALUE_OPTIONAL, 'Add headers request');
        $this->addOption('cookies', 'cs', InputOption::VALUE_OPTIONAL, 'Add cookies request');
        $this->addOption('config', 'cn', InputOption::VALUE_OPTIONAL, 'Path to config file');
        $this->addOption('repl', 'r', InputOption::VALUE_NONE, 'End in REPL mode');

        $this->addUsage('sasablagojevic.com');
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $configPath = $input->getOption('config');
        if ($configPath !== null && file_exists($configPath)) {
            if (str_ends_with($configPath, '.php')) {
                $config = require $configPath;
            }
        }
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $url = $input->getArgument('url');

        if ($url === null) {
            $whereToHelper = $this->getHelper('question');
            \assert($whereToHelper instanceof QuestionHelper);

            $question = (new Question('Hey you didn\'t provide a url. What web address do you want to visit: '))
                ->setValidator(new UrlValidator())
                ->setMaxAttempts(3);

            $input->setArgument('url', $whereToHelper->ask($input, $output, $question));
        }
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $url = $this->getUrlArgument($input);

        $website = $site = $w = $this->getCrawler($output)->crawlWebsite($url);

        if ($this->isREPLMode($input)) {
            // ===============================================================================
            // REPL MODE
            \Psy\debug(compact('website', 'site', 'w'));
            // $website, $site, $w, - variables pointing to the same crawled website object
            // ===============================================================================
            return Command::SUCCESS;
        }

        $output->writeln("Crawled {$website->count()} pages.");


        $action = $this->getActionChoice($input, $output);

        $selectedElement = null;

        match ($action) {
            Action::PRINT_TEXT => $output->writeln($website->pageAt(0)?->text()),
            Action::PRINT_HTML => $output->writeln($website->pageAt(0)?->html()),
            Action::NAVIGATE_TO => $this->getLinkChoice($website, $input, $output),
            Action::SELECT_ELEMENT => $this->selectDomElement($input, $output),
            default => throw new InvalidActionChoice()
        };

        return Command::SUCCESS;
    }

    private function getActionChoice(InputInterface $input, OutputInterface $output): Action
    {
        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            'What do you want to do next?',
            [
                Action::PRINT_TEXT->value => 'Print text',
                Action::PRINT_HTML->value => 'Print html',
                Action::SELECT_ELEMENT->value => 'Select DOM element',
                Action::NAVIGATE_TO->value => 'Navigate to a different page'
            ],
            Action::PRINT_TEXT->value
        );
        $question->setErrorMessage('%s is invalid choice value.');

        return Action::from((int) $helper->ask($input, $output, $question));
    }

    private function getCrawler(OutputInterface $output): Crawler
    {
        return new Spider(
            new HttpBrowser(HttpClient::create(['timeout' => 60])),
            $output,
            new ProgressBar($output)
        );
    }

    private function getLinkChoice(Website $website, InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            'Which link do you want to browse?',
            $website->links()->each(fn (Link $link) => $link->href())->all(),
        );
        $question->setErrorMessage('%s is invalid choice value.');
        return (int) $helper->ask($input, $output, $question);
    }

    private function selectDomElement(InputInterface $input, OutputInterface $output)
    {
    }
}
