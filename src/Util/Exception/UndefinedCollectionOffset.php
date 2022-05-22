<?php

declare(strict_types=1);

namespace SasaB\REPLCrawler\Util\Exception;

use SasaB\REPLCrawler\Util\Collection;

class UndefinedCollectionOffset extends \InvalidArgumentException
{
    public static function for(Collection $collection, int $offset): self
    {
        return new self(sprintf("Undefined offset [%s] in collection %s", $offset, $collection::class));
    }
}
