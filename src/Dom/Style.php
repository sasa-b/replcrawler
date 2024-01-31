<?php

declare(strict_types=1);

namespace Sco\REPLCrawler\Dom;

final class Style extends DomElement
{
    public function isLink(): bool
    {
        return $this->crawler()->nodeName() === 'link';
    }
}
