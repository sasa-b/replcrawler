<?php

declare(strict_types=1);

namespace SasaB\REPLCrawler\Website;

use SasaB\REPLCrawler\Util\CanDelegateProperty;
use SasaB\REPLCrawler\Util\Collection;

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

    public function pagesWithTitle(string $title): Pages
    {
        return new Pages($this->filter(fn (Webpage $page) => $page->title() === $title)->all());
    }

    public function pages(): Pages
    {
        return new Pages($this->all());
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
