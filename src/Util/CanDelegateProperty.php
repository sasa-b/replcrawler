<?php

declare(strict_types=1);

namespace Sco\REPLCrawler\Util;

trait CanDelegateProperty
{
    public function __get(string $method): mixed
    {
        if (method_exists($this, $method)) {
            return $this->{$method}();
        }
        return null;
    }
}
