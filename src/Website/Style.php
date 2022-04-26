<?php

declare(strict_types=1);

namespace SasaB\REPLCrawler\Website;

final class Style extends DomElement
{
    public function isLink(): bool
    {
        return $this->crawler()->nodeName() === 'link';
    }
}
