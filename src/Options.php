<?php

declare(strict_types=1);

namespace SasaB\REPLCrawler;

class Options
{
    public function __construct(
        public string $method = 'GET',
        public array $parameters = [],
        public array $server = [],
        public array $files = [],
        public bool $ignoreURLHash = true
    ) {
    }

    /**
     * @param array<string,string> $headers
     */
    final public function addHeaders(array $headers): self
    {
        foreach ($headers as $header => $value) {
            $header = strtoupper($header);
            $header = str_starts_with($header, 'HTTP_') === false ? "HTTP_$header" : $header;
            $header = str_replace('-', '_', $header);
            $this->server[$header] = $value;
        }
        return $this;
    }
}
