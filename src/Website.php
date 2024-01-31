<?php

declare(strict_types=1);

namespace Sco\REPLCrawler;

use Sco\REPLCrawler\Dom\Links;
use Sco\REPLCrawler\Util\CanDelegateProperty;
use Sco\REPLCrawler\Util\Collection;

/**
 * @extends Collection<Webpage>
 */
class Website extends Collection
{
    use CanDelegateProperty;

    public function pageAt(int $position): ?Webpage
    {
        return $this->items[$position] ?? null;
    }

    public function pageWithTitle(string $title): ?Webpage
    {
        return $this->filter(fn (Webpage $page) => $page->title() === $title)->current();
    }

    /**
     * @return Collection<Webpage>
     */
    public function pages(): Collection
    {
        return new Collection($this->all());
    }

    final public function links(): Links
    {
        $links = [];
        foreach ($this->all() as $page) {
            foreach ($page->links()->internal() as $link) {
                $links[$link->href()] = $link;
            }
        }
        return new Links(array_unique(array_values($links), SORT_REGULAR));
    }
}
