<?php

declare(strict_types=1);

namespace Sco\REPLCrawler\Cli\Validator;

class UrlValidator
{
    public function __invoke(string|null $input): string
    {
        $input = (string) $input;
        $input = str_contains($input, 'http') ? $input : "http://$input";

        if (!is_string(filter_var($input, FILTER_VALIDATE_URL))) {
            throw new \RuntimeException('Invalid url value');
        }

        return $input;
    }
}
