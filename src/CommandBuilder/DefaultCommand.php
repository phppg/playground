<?php

declare(strict_types=1);

namespace Playground\CommandBuilder;

use Playground\CommandBuilder;

class DefaultCommand implements CommandBuilder
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param array{file:string} $options
     * @return string[]
     */
    public function build(array $options): array
    {
        return [$this->name, '-f', $options['file']];
    }
}
