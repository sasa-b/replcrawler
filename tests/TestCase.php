<?php

declare(strict_types=1);

namespace SasaB\REPLCrawler\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    protected function assertArrayHasUniqueValues(array $array): void
    {
        $this->assertSame($array, array_unique($array));
    }
}
