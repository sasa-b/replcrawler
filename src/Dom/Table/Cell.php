<?php

declare(strict_types=1);

namespace Sco\REPLCrawler\Dom\Table;

use Sco\REPLCrawler\Dom\DomElement;
use Sco\REPLCrawler\Webpage;
use Symfony\Component\DomCrawler\Crawler as ElementCrawler;

class Cell extends DomElement
{
    private Row $row;

    public function __construct(
        Row $row,
        ElementCrawler $elementCrawler,
        ?Webpage $webPage = null
    ) {
        parent::__construct($elementCrawler, $webPage);
        $this->row = $row;
    }

    public function row(): Row
    {
        return $this->row;
    }
}
