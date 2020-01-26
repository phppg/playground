<?php

declare(strict_types=1);

namespace Playground\CommandBuilder;

use Playground\CommandBuilder;

class DefaultCommand implements CommandBuilder
{
    private string $name;
    private ?string $ini;

    public function __construct(string $name, string $ini = null)
    {
        $this->name = $name;
        $this->ini = $ini;
    }

    /**
     * @param array{file:string} $options
     * @return string[]
     */
    public function build(array $options): array
    {
        $args = ['-f', $options['file']];

        if ($this->ini !== null) {
            $args = ['-c', $this->ini, ...$args];
        }

        return [$this->name, ...$args];
    }
}
