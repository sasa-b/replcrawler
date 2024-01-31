<?php

declare(strict_types=1);

namespace Sco\REPLCrawler;

interface Crawler
{
    public function crawl(string $url, Options $options = new Options()): Webpage;

    public function crawlWebsite(string $url, Options $options = new Options()): Website;

    public function crawlHtml(string $html, ?string $url = null, Options $options = new Options()): Webpage;
}
