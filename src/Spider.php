<?php

declare(strict_types=1);

namespace SasaB\REPLCrawler;

use Goutte\Client;
use SasaB\REPLCrawler\Website\Webpage;
use SasaB\REPLCrawler\Website\Website;
use Symfony\Component\DomCrawler\Crawler as ElementCrawler;

class Spider implements Crawler
{
    public function __construct(private readonly Client $client)
    {
    }

    final public function crawl(string $url, Options $options = new Options(), string $body = null): Webpage
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

    final public function crawlWebsite(string $url, Options $options = new Options()): Website
    {
        return new Website(
            array_values(
                $this->followInternalLinks($this->crawl($url, $options))
            )
        );
    }

    final public function crawlHtml(string $html): Webpage
    {
        return new Webpage($this, new ElementCrawler($html));
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
                continue;
            }

            $newPage = $link->follow();
            if ($newPage->html() === $page->html()) {
                continue;
            }

            $crawled[$link->href()] = $newPage;

            if ($newPage->links()->internal()->isNotEmpty()) {
                $this->followInternalLinks($newPage, $crawled);
            }
        }

        return $crawled;
    }
}
