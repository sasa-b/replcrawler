<?php

declare(strict_types=1);

namespace Sco\REPLCrawler\Cli;

use Sco\REPLCrawler\Crawler;
use Sco\REPLCrawler\Options;
use Sco\REPLCrawler\Webpage;
use Sco\REPLCrawler\Website;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler as ElementCrawler;

final readonly class Spider implements Crawler
{
    public function __construct(
        private HttpBrowser $client,
        private OutputInterface $output,
        private ProgressBar $progressBar,
        private bool $showProgressBar = true
    ) {
        $this->progressBar->setFormat('[%bar%] Mem: %memory%');
        $this->progressBar->setBarCharacter('<comment>=</comment>');
    }

    public function crawl(string $url, Options $options = new Options(), string $body = null): Webpage
    {
        return new Webpage(
            $this,
            $this->client->request(
                $options->method,
                $url,
                $options->parameters,
                $options->files,
                $options->server,
                $body
            )
        );
    }

    public function crawlWebsite(string $url, Options $options = new Options()): Website
    {
        $this->output->writeln("Crawling: $url");

        if ($this->showProgressBar) {
            $this->progressBar->display();
        }

        $page = $this->crawl($url, $options);

        if ($this->showProgressBar) {
            $this->progressBar->advance();
        }

        $website = new Website(
            array_values(
                $this->followInternalLinks($page)
            )
        );

        if ($this->showProgressBar) {
            $this->progressBar->finish();
        }

        $this->output->write("\n");

        return $website;
    }

    public function crawlHtml(string $html, ?string $url = null, Options $options = new Options()): Webpage
    {
        return new Webpage($this, new ElementCrawler(node: $html, uri: $url));
    }

    /**
     * @param Webpage $page
     * @param array<string, Webpage> $crawled
     * @return array<string, Webpage>
     */
    private function followInternalLinks(Webpage $page, array &$crawled = []): array
    {
        $crawled[$page->url()->toString()] = $page;

        $internalLinks = $page->links()->internal();

        foreach ($internalLinks as $link) {
            if (isset($crawled[$link->href()])) {
                if ($this->showProgressBar) {
                    $this->progressBar->advance();
                }
                continue;
            }

            $newPage = $link->follow();
            if ($this->showProgressBar) {
                $this->progressBar->advance();
            }

            if ($newPage->html() === $page->html()) {
                if ($this->showProgressBar) {
                    $this->progressBar->advance();
                }
                continue;
            }

            $crawled[$link->href()] = $newPage;

            if (!$this->showProgressBar) {
                $this->output->writeln("Crawled: {$link->href()}");
            }

            if ($newPage->links()->internal()->isNotEmpty()) {
                $this->followInternalLinks($newPage, $crawled);
            }
        }

        return $crawled;
    }
}
