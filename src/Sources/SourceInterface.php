<?php

namespace Pforret\SunnySideUp\Sources;

use Pforret\SunnySideUp\Formats\ProductionResponse;

interface SourceInterface
{
    public function get(string $url): ProductionResponse;
}
