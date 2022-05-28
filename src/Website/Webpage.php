<?php

declare(strict_types=1);

namespace SasaB\REPLCrawler\Website;

use SasaB\REPLCrawler\Crawler;
use SasaB\REPLCrawler\Util\Collection;
use Symfony\Component\DomCrawler\Crawler as ElementCrawler;

class Webpage extends DomElement
{
    /**
     * @param Crawler $spider
     * @param ElementCrawler $elementCrawler
     */
    public function __construct(
        private readonly Crawler $spider,
        ElementCrawler $elementCrawler
    ) {
        parent::__construct($elementCrawler);
    }

    final public function links(): Links
    {
        $links = $this->crawler()
            ->filter('a')
            ->each(fn (ElementCrawler $elementCrawler) => new Link($this->spider, $elementCrawler, $this));

        return new Links(array_unique($links, SORT_REGULAR));
    }

    final public function body(): Body
    {
        return new Body($this->crawler()->filter('body'), $this);
    }

    final public function head(): Head
    {
        return new Head($this->crawler()->filter('head'), $this);
    }

    final public function title(): string
    {
        return $this->crawler()->filter('title')->text();
    }

    /**
     * @return Collection<Script>
     */
    final public function scripts(): Collection
    {
        return new Collection(
            $this->crawler()
                ->filter('script')
                ->each(fn (ElementCrawler $elementCrawler) => new Script($elementCrawler, $this))
        );
    }

    /**
     * @return Collection<Style>
     */
    final public function styles(): Collection
    {
        return new Collection(
            array_merge(
                $this->crawler()
                    ->filter('style')
                    ->each(fn (ElementCrawler $elementCrawler) => new Style($elementCrawler, $this)),
                $this->crawler()
                    ->filter('link[rel=stylesheet]')
                    ->each(fn (ElementCrawler $elementCrawler) => new Style($elementCrawler, $this))
            )
        );
    }

    /**
     * @param string $selector
     * @return Collection<DomElement>
     */
    final public function querySelector(string $selector): Collection
    {
        return new Collection(
            $this->crawler()->filter($selector)->each(fn (ElementCrawler $crawler) => new DomElement($crawler, $this))
        );
    }
}
