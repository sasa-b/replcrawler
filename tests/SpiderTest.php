<?php

declare(strict_types=1);

namespace SasaB\REPLCrawler\Tests;

use Goutte\Client;
use PHPUnit\Framework\TestCase;
use SasaB\REPLCrawler\Crawler;
use SasaB\REPLCrawler\Spider;
use SasaB\REPLCrawler\Website\Webpage;
use Symfony\Component\HttpClient\HttpClient;

class SpiderTest extends TestCase
{
    private Crawler $fixture;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fixture = new Spider(new Client(HttpClient::create(['timeout' => 60])));
    }

    public function test_it_can_crawl_website(): void
    {
        $website = $this->fixture->crawlWebsite('https://sasablagojevic.com');

        $this->assertGreaterThan(0, $website->count());
        $this->assertInstanceOf(Webpage::class, $website->pageAt(0));

        var_dump(array_map(fn (Webpage $page) => $page->title(), $website->pages()));
    }
}
