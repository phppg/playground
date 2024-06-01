<?php

declare(strict_types=1);

namespace Playground\Code;

use Playground\Code as CodeInterface;

final readonly class SourceCode implements CodeInterface
{
    public function __construct(
        private string $source
    ) {
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
