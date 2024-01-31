<?php

declare(strict_types=1);

namespace Sco\REPLCrawler\Dom;

use Sco\REPLCrawler\Dom\Table\Cell;
use Sco\REPLCrawler\Dom\Table\Row;
use Sco\REPLCrawler\Util\Collection;
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
                ->each(fn (ElementCrawler $elementCrawler) => new Row($elementCrawler, $this->page()))
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
