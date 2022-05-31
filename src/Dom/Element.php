<?php

declare(strict_types=1);

namespace SasaB\REPLCrawler\Dom;

use SasaB\REPLCrawler\Webpage;
use Symfony\Component\DomCrawler\Crawler as ElementCrawler;

interface Element
{
    public function text(): string;

    public function html(): string;

    public function crawler(): ElementCrawler;

    public function nodeName(): string;

    public function page(): ?Webpage;

    public function attribute(string $name): ?string;
}
