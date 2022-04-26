<?php

declare(strict_types=1);

namespace SasaB\REPLCrawler\Website;

use Symfony\Component\DomCrawler\Crawler as ElementCrawler;

final class Head extends DomElement
{
    public function meta(): Metas
    {
        return new Metas(
            $this->crawler()
                ->filter('meta')
                ->each(fn (ElementCrawler $crawler) => new Meta($this->crawler(), $this->page()))
        );
    }
}
