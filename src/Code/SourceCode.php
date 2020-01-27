<?php

declare(strict_types=1);

namespace Playground\Code;

use Playground\Code as CodeInterface;

final class SourceCode implements CodeInterface
{
    private string $source;

    public function __construct(string $source)
    {
        $this->source = $source;
    }

    public function __toString(): string
    {
        return $this->source;
    }

    public function getSource(): ?CodeInterface
    {
        return null;
    }
}
