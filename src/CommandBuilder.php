<?php

declare(strict_types=1);

namespace Playground;

interface CommandBuilder
{
    /** @phpstan-return non-empty-list<string> */
    public function build(string $file): array;
}
