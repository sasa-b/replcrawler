<?php

declare(strict_types=1);

namespace SasaB\REPLCrawler\Dom;

final class Style extends DomElement
{
    public function isLink(): bool
    {
        return $this->crawler()->nodeName() === 'link';
    }
}
