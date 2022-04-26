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

    /**
     * @return array<Webpage>
     */
    public function pages(): array
    {
        return $this->all();
    }

    final public function links(): Links
    {
        $links = [];
        foreach ($this->all() as $page) {
            $links = [...$links, ...$page->links()->all()];
        }
        return new Links(array_unique($links, SORT_REGULAR));
    }
}
