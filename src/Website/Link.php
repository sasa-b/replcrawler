<?php

declare(strict_types=1);

namespace SasaB\REPLCrawler\Website;

use SasaB\REPLCrawler\Crawler;
use SasaB\REPLCrawler\Util\CanNormalizeURI;
use Symfony\Component\DomCrawler\Crawler as ElementCrawler;

final class Link extends DomElement implements \Stringable
{
    use CanNormalizeURI;

    private readonly string $href;

    /**
     * @param Crawler $spider
     * @param Webpage $webPage
     * @param ElementCrawler $elementCrawler
     */
    public function __construct(
        private        readonly Crawler $spider,
        ElementCrawler $elementCrawler,
        Webpage $webPage
    ) {
        parent::__construct($elementCrawler, $webPage);

        $this->href = $this->normalize($elementCrawler->link()->getUri());
    }

    public function href(): string
    {
        return $this->href;
    }

    public function hrefUrl(): URL
    {
        return new URL($this->href);
    }

    public function __toString(): string
    {
        return $this->href;
    }

    public function follow(): Webpage
    {
        return $this->spider->crawl($this->href);
    }

    public function isInternal(): bool
    {
        return str_contains($this->href, (string) $this->baseUrl()->toUri());
    }

    public function isExternal(): bool
    {
        return !$this->isInternal();
    }
}
