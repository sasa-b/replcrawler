<?php

declare(strict_types=1);

namespace SasaB\REPLCrawler\Tests;

use Goutte\Client;
use SasaB\REPLCrawler\Spider;
use SasaB\REPLCrawler\Website\Link;
use SasaB\REPLCrawler\Website\Webpage;
use SasaB\REPLCrawler\Website\Website;
use Symfony\Component\HttpClient\HttpClient;

class SpiderTest extends TestCase
{
    private Spider $fixture;

    private Website $crawledWebsite;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fixture = new Spider(new Client(HttpClient::create(['timeout' => 60])));
    }

    private function getCrawledWebsite(): Website
    {
        return $this->crawledWebsite ??= $this->fixture->crawlWebsite('https://sasablagojevic.com');
    }

    public function test_it_can_crawl_website(): void
    {
        $website = $this->getCrawledWebsite();

        $this->assertGreaterThanOrEqual(72, $website->count());

        $indexPage = $website->pageAt(0);

        $this->assertInstanceOf(Webpage::class, $indexPage);
        $this->assertSame('https://sasablagojevic.com', (string) $indexPage->url());
    }

    public function test_it_only_crawls_unique_links(): void
    {
        $website = $this->getCrawledWebsite();

        $this->assertArrayHasUniqueValues(
            array_map(static fn (Link $link) => $link->href(), $website->links()->internal()->withoutHash()->all())
        );

        $this->assertArrayHasUniqueValues(
            array_map(static fn (Link $link) => $link->href(), $website->links()->internal()->all())
        );
    }

    public function test_it_can_crawl_website_from_html(): void
    {
        $html = trim((string) file_get_contents(__DIR__.'/data/sasablagojevic.html'), "\n");

        $indexPage = $this->fixture->crawlHtml($html);

        $this->assertSame($html, $indexPage->html());
    }
}
