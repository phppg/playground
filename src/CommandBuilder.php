<?php

declare(strict_types=1);

namespace Playground;

interface CommandBuilder
{
    /**
     * @return string[]
     */
    public function build(string $file): array;
}
