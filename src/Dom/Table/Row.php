<?php

declare(strict_types=1);

namespace Sco\REPLCrawler\Dom\Table;

use Sco\REPLCrawler\Dom\DomElement;
use Sco\REPLCrawler\Util\Collection;
use Sco\REPLCrawler\Webpage;
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
                ->each(fn (ElementCrawler $elementCrawler) => new Cell($this, $elementCrawler, $this->page()))
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
