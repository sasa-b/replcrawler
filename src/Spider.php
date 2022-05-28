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
                $this->followInternalLinks(page: $this->crawl($url, $options), stripHashFromURL: $options->ignoreURLHash)
            )
        );
    }

    final public function crawlHtml(string $html, ?string $url = null, Options $options = new Options()): Webpage
    {
        return new Webpage($this, new ElementCrawler(node: $html, uri: $url));
    }

    /**
     * @param Webpage $page
     * @param array $crawled
     * @param bool $stripHashFromURL
     * @return array<string, Webpage>
     */
    private function followInternalLinks(Webpage $page, array &$crawled = [], bool $stripHashFromURL = true): array
    {
        $pageUrl = (string) (
            $stripHashFromURL
                ? $page->url()->withoutHash()
                : $page->url()
        );

        $crawled[$pageUrl] = $page;

        $internalLinks = $page->links()->internal();

        foreach ($internalLinks as $link) {
            $href = (string) (
                $stripHashFromURL
                    ? $link->hrefUrl()->withoutHash()
                    : $link->hrefUrl()
            );

            if (isset($crawled[$href])) {
                continue;
            }

            $newPage = $link->follow();
            // TODO: add algorithm to match with similar_text() when we have links with #hash
            if ($newPage->html() === $page->html()) {
                continue;
            }

            $crawled[$href] = $newPage;

            if ($newPage->links()->internal()->isNotEmpty()) {
                $this->followInternalLinks($newPage, $crawled, $stripHashFromURL);
            }
        }

        return $crawled;
    }
}
