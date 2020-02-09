<?php

declare(strict_types=1);

namespace Playground\CommandBuilder;

use Playground\CommandBuilder;

class DefaultCommand implements CommandBuilder
{
    /** @var array<string,string> */
    private array $defines;
    private ?string $ini;
    private string $name;
    private bool $noconf;

    /**
     * @param array{name:string,ini?:?string,noconf?:bool,defines?:array<string,string>} $options
     */
    public function __construct(array $options)
    {
        $this->name = $options['name'];
        $this->ini = $options['ini'] ?? null;
        $this->noconf = $options['noconf'] ?? false;
        $this->defines = $options['defines'] ?? [];
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

        foreach ($this->defines as $key => $value) {
            $args[] = '-d';
            $args[] = "{$key}={$value}";
        }

        if ($this->ini !== null) {
            $args = [...$args, '-c', $this->ini];
        }

        return [$this->name, ...$args, '-f', $file];
    }
}
