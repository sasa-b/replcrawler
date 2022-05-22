<?php

declare(strict_types=1);

namespace SasaB\REPLCrawler\Util;

trait CanNormalizeURI
{
    private function normalize(string $uri): string
    {
        if (str_contains('#', $uri) && !str_contains('/#', $uri)) {
            $uri = str_replace('#', '/#', $uri);
        }
        return rtrim($uri, '/');
    }

    private function stripHash(string $uri): string
    {
        [$uriWithoutHash] = explode('#', $uri);
        return $uriWithoutHash;
    }
}
