<?php

declare(strict_types=1);

namespace Playground;

use function Safe\file_put_contents;

class File
{
    protected string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function write(Code $code): void
    {
        file_put_contents($this->path, $code);
    }
}
