<?php

declare(strict_types=1);

namespace SasaB\REPLCrawler\Website;

use SasaB\REPLCrawler\Util\CanDelegateProperty;
use Symfony\Component\DomCrawler\Crawler as ElementCrawler;

class DomElement implements Element
{
    use CanDelegateProperty;

    public function __construct(
        private readonly ElementCrawler $elementCrawler,
        private readonly ?Webpage $webPage = null,
    ) {
    }

    public function page(): ?Webpage
    {
        return $this->webPage;
    }

    public function crawler(): ElementCrawler
    {
        return $this->elementCrawler;
    }

    public function text(): string
    {
        return $this->elementCrawler->text();
    }

    public function html(): string
    {
        return $this->elementCrawler->html();
    }

    public function nodeName(): string
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
