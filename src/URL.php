<?php

declare(strict_types=1);

namespace Sco\REPLCrawler;

use Sco\REPLCrawler\Util\CanNormalizeURI;

final class URL implements \Stringable
{
    use CanNormalizeURI;

    private string $url;

    public function __construct(string $url)
    {
        $this->url = $this->normalize(str_contains($url, 'http') === false ? "http://$url" : $url);
    }

    public static function fromURI(URI $uri): self
    {
        return new self((string) $uri);
    }

    public function toUri(): URI
    {
        return URI::fromURL($this);
    }

    public function __toString(): string
    {
        return $this->url;
    }

    public function toString(): string
    {
        return $this->url;
    }

    public function base(): URL
    {
        $url = (string) preg_replace('/#.*$/', '', $this->url);

        [$protocol, $uri] = explode('://', $url);

        [$baseUri] = explode('/', $uri);

        return new self("$protocol://$baseUri");
    }

    public function withoutHash(): URL
    {
        return new self($this->stripHash($this->url));
    }

    public function equals(URL|URI $compare): bool
    {
        if ($compare instanceof URI) {
            return (string) $this->toUri() === (string) $compare;
        }
        return $this->url === (string) $compare;
    }
}
