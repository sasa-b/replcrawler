<?php

declare(strict_types=1);

namespace Sco\REPLCrawler;

use Sco\REPLCrawler\Util\CanNormalizeURI;

final class URI implements \Stringable
{
    use CanNormalizeURI;

    private string $uri;

    public function __construct(string $uri)
    {
        $this->uri = $this->normalize($uri);
    }

    public static function fromURL(URL $url): URI
    {
        return new self(str_replace(['http://', 'https://'], '', (string) $url));
    }

    public function toUrl(): URL
    {
        return URL::fromURI($this);
    }

    public function __toString(): string
    {
        return $this->uri;
    }

    public function toString(): string
    {
        return $this->uri;
    }

    public function base(): URI
    {
        $uri = (string) preg_replace('/#.*$/', '', $this->uri);

        [$baseUri] = explode('/', $uri);

        return new self($baseUri);
    }

    public function withoutHash(): URI
    {
        return new self($this->stripHash($this->uri));
    }

    public function equals(URI|URL $compare): bool
    {
        return $this->uri === (string) ($compare instanceof URL ? $compare->toUri() : $compare);
    }

    public function path(): string
    {
        return str_replace($this->base()->toString(), '', $this->uri);
    }
}
