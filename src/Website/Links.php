<?php

declare(strict_types=1);

namespace SasaB\REPLCrawler\Website;

use SasaB\REPLCrawler\Util\Collection;
use SasaB\REPLCrawler\Util\Exception\UndefinedCollectionOffset;

/**
 * @extends Collection<Link>
 */
class Links extends Collection
{
    final public function at(int $position): Link
    {
        $link = $this->offsetGet($position);

        if ($link === null) {
            throw UndefinedCollectionOffset::for($this, $position);
        }

        return $link;
    }

    final public function internal(): Links
    {
        return new self(
            array_values(
                array_filter(array_unique($this->items, SORT_REGULAR), static fn (Link $link) => $link->isInternal())
            )
        );
    }

    final public function external(): Links
    {
        return new self(
            array_values(
                array_filter(array_unique($this->items, SORT_REGULAR), static fn (Link $link) => $link->isExternal())
            )
        );
    }

    /**
     * @return array<string>
     */
    final public function href(): array
    {
        return array_unique(array_map(static fn (Link $link) => $link->href(), $this->items));
    }

    /**
     * @return array<URL>
     */
    final public function hrefUrl(): array
    {
        return array_unique(array_map(static fn (Link $link) => $link->hrefUrl(), $this->items), SORT_REGULAR);
    }

    /**
     * @return array<string, mixed>
     */
    final public function hrefMap(): array
    {
        $map = [];
        foreach ($this->items as $link) {
            $title = $link->text() !== '' ? $link->text() : 'No title';
            $href = $link->href();

            if (!isset($map[$title])) {
                $map[$title] = $href;
                continue;
            }

            if (is_array($map[$title])) {
                $map[$title][] = $href;
            } else {
                $map[$title] = [$map[$title], $href];
            }

            $map[$title] = array_unique($map[$title]);

            if (count($map[$title]) === 1) {
                $map[$title] = $map[$title][0];
            }
        }
        return $map;
    }

    /**
     * @return array<string, URL>
     */
    final public function hrefUrlMap(): array
    {
        $map = $this->hrefMap();
        foreach ($map as $href => $mixed) {
            if (is_array($mixed)) {
                $map[$href] = array_map(static fn (string $url) => new URL($url), $mixed);
            } else {
                $map[$href] = new URL($mixed);
            }
        }
        return $map;
    }
}
