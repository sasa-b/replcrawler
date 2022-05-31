<?php

declare(strict_types=1);

namespace SasaB\REPLCrawler\Dom;

use SasaB\REPLCrawler\URL;
use SasaB\REPLCrawler\Util\CanDelegateProperty;
use SasaB\REPLCrawler\Webpage;
use Symfony\Component\DomCrawler\Crawler as ElementCrawler;

class DomElement implements Element
{
    use CanDelegateProperty;

    public function __construct(
        private readonly ElementCrawler $elementCrawler,
        private readonly ?Webpage $webPage = null,
    ) {
    }

    final public function page(): ?Webpage
    {
        return $this->webPage;
    }

    final public function crawler(): ElementCrawler
    {
        return $this->elementCrawler;
    }

    final public function text(): string
    {
        return $this->elementCrawler->text();
    }

    final public function html(): string
    {
        return $this->elementCrawler->html();
    }

    final public function nodeName(): string
    {
        return strtolower($this->elementCrawler->nodeName());
    }

    final public function url(): URL
    {
        return new URL($this->crawler()->getUri() ?? '');
    }

    final public function baseUrl(): URL
    {
        return new URL($this->crawler()->getBaseHref() ?? '');
    }

    final public function attribute(string $name): ?string
    {
        return $this->crawler()->attr($name);
    }
}
