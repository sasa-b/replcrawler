<?php

declare(strict_types=1);

namespace SasaB\REPLCrawler\Util;

trait CanNormalizeURI
{
    public function normalize(string $uri): string
    {
        return rtrim(str_replace(['/#', '#'], ['/#', '/#'], $uri), '/');
    }
}
