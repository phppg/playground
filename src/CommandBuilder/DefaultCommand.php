<?php

declare(strict_types=1);

namespace Playground\CommandBuilder;

use Playground\CommandBuilder;

class DefaultCommand implements CommandBuilder
{
    private ?string $ini;
    private string $name;
    private bool $noconf;

    /**
     * @param array{name:string,ini?:?string,noconf:bool} $options
     */
    public function __construct(array $options)
    {
        $this->name = $options['name'];
        $this->ini = $options['ini'] ?? null;
        $this->noconf = $options['noconf'] ?? false;
    }

    /**
     * @return string[]
     */
    public function build(string $file): array
    {
        $args = [];

        if ($this->noconf) {
            $args[] = '-n';
        }

        if ($this->ini !== null) {
            $args = [...$args, '-c', $this->ini];
        }

        return [$this->name, ...$args, '-f', $file];
    }
}
