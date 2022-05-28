<?php

declare(strict_types=1);

namespace SasaB\REPLCrawler\Website;

use SasaB\REPLCrawler\Util\Collection;
use Symfony\Component\DomCrawler\Crawler as ElementCrawler;

final class Head extends DomElement
{
    /**
     * @return Collection<Meta>
     */
    public function meta(): Collection
    {
        return new Collection(
            $this->crawler()
                ->filter('meta')
                ->each(fn (ElementCrawler $crawler) => new Meta($this->crawler(), $this->page()))
        );
    }
}
