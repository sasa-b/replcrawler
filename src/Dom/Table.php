<?php

declare(strict_types=1);

namespace SasaB\REPLCrawler\Dom;

use SasaB\REPLCrawler\Dom\Table\Cell;
use SasaB\REPLCrawler\Dom\Table\Row;
use SasaB\REPLCrawler\Util\Collection;
use Symfony\Component\DomCrawler\Crawler as ElementCrawler;

final class Table extends DomElement
{
    public function rowAt(int $rowIndex): ?Row
    {
        return $this->rows()[$rowIndex] ?? null;
    }

    public function cellAt(int $rowIndex, int $cellIndex): ?Cell
    {
        return $this->rows()[$rowIndex]?->cellAt($cellIndex);
    }

    /**
     * @return Collection<Row>
     */
    public function rows(): Collection
    {
        return new Collection(
            $this->crawler()
                ->filter('tbody tr')
                ->each(fn (ElementCrawler $element) => new Row($element, $this->page()))
        );
    }

    /**
     * @return array<int,Collection<Cell>>
     */
    public function cells(): array
    {
        $cells = [];
        foreach ($this->rows() as $i => $row) {
            $cells[$i] = $row->cells();
        }
        return $cells;
    }
}
