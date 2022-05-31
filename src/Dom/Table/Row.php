<?php

declare(strict_types=1);

namespace SasaB\REPLCrawler\Dom\Table;

use SasaB\REPLCrawler\Dom\DomElement;
use SasaB\REPLCrawler\Util\Collection;
use SasaB\REPLCrawler\Webpage;
use Symfony\Component\DomCrawler\Crawler as ElementCrawler;

class Row extends DomElement
{
    /**
     * @var Collection<Cell>
     */
    private Collection $cells;

    public function __construct(
        ElementCrawler $elementCrawler,
        ?Webpage $webPage = null
    ) {
        parent::__construct($elementCrawler, $webPage);

        $this->cells = new Collection(
            $this->crawler()
                ->filter('td')
                ->each(fn (ElementCrawler $element) => new Cell($this, $element, $this->page()))
        );
    }

    /**
     * @return Collection<Cell>
     */
    public function cells(): Collection
    {
        return $this->cells;
    }

    public function cellAt(int $index): ?Cell
    {
        return $this->cells[$index] ?? null;
    }
}
