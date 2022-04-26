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
    ) {
    }

    /**
     * @param array<string,string> $headers
     */
    public function addHeaders(array $headers): self
    {
        foreach ($headers as $header => $value) {
            $header = strtoupper($header);
            $header = str_starts_with($header, 'HTTP_') === false ? "HTTP_$header" : $header;
            $this->server[$header] = $value;
        }
        return $this;
    }
}
