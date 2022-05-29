<?php

declare(strict_types=1);

namespace SasaB\REPLCrawler\Cli;

enum Action: int
{
    case READ_PAGE = 0;
    case SELECT_ELEMENT = 1;
    case NAVIGATE_TO = 2;
}
