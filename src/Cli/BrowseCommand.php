<?php

declare(strict_types=1);

namespace SasaB\REPLCrawler\Cli;

use Goutte\Client;
use SasaB\REPLCrawler\Cli\Validator\UrlValidator;
use SasaB\REPLCrawler\Crawler;
use SasaB\REPLCrawler\Spider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
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
        return $input->hasOption('repl');
    }

    protected function configure(): void
    {
        $this->addArgument('url', InputArgument::REQUIRED);

        $this->addOption('headers', 'hs', InputOption::VALUE_OPTIONAL);
        $this->addOption('cookies', 'cs', InputOption::VALUE_OPTIONAL);
        $this->addOption('config', 'cn', InputOption::VALUE_OPTIONAL);
        $this->addOption('repl', 'r', InputOption::VALUE_NONE);

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

        $webpage = $page = $w = $p = $this->getCrawler()->crawlWebsite($url);

        if ($this->isREPLMode($input)) {
            // ===============================================================================
            // REPL MODE
            \Psy\debug(compact('webpage', 'page', 'w', 'p'));
            // $webpage, $page, $w, $p - variables pointing to the same crawled webpage object
            // ===============================================================================
        }


//        var_dump(
//            $webpage->url(),
//            $webpage->links()->href(),
//            $webpage->links()->internal()->href(),
//            $webpage->links()->hrefMap(),
//        );
//

        return Command::SUCCESS;
    }

    private function getCrawler(): Crawler
    {
        return new Spider(new Client(HttpClient::create(['timeout' => 60])));
    }
}
