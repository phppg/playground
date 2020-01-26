<?php

declare(strict_types=1);

namespace Playground;

interface CommandBuilder
{
    /**
     * @param array{file:string} $options
     * @return string[]
     */
    public function build(array $options): array;
}
