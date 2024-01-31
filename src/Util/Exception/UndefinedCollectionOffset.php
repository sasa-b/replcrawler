<?php

declare(strict_types=1);

namespace Sco\REPLCrawler\Util\Exception;

use Sco\REPLCrawler\Util\Collection;

class UndefinedCollectionOffset extends \InvalidArgumentException
{
    public static function for(Collection $collection, int $offset): self
    {
        return new self(sprintf("Undefined offset [%s] in collection %s", $offset, $collection::class));
    }
}
