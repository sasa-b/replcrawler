<?php

declare(strict_types=1);

namespace SasaB\REPLCrawler;

use SasaB\REPLCrawler\Website\Webpage;
use SasaB\REPLCrawler\Website\Website;

interface Crawler
{
    public function crawlHtml(string $html): Webpage;

    public function crawlWebsite(string $url, Options $options = new Options()): Website;

    public function crawl(string $url, Options $options = new Options()): Webpage;
}
