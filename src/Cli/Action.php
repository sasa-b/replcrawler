<?php

declare(strict_types=1);

namespace Sco\REPLCrawler\Cli;

enum Action: int
{
    case PRINT_TEXT = 0;
    case PRINT_HTML = 1;
    case SELECT_ELEMENT = 2;
    case NAVIGATE_TO = 3;
}
