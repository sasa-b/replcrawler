<?php

declare(strict_types=1);

namespace Sco\REPLCrawler\Dom;

use Symfony\Component\DomCrawler\Crawler as ElementCrawler;

final class Body extends DomElement
{
    public function header(): ElementCrawler
    {
        return $this->crawler()->filter('header');
    }

    public function footer(): ElementCrawler
    {
        return $this->crawler()->filter('footer');
    }
}
