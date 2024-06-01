<?php

declare(strict_types=1);

namespace Playground;

use Symfony\Component\Process\Process;

/**
 * PHP code invoker
 */
interface Invoker
{
    /**
     * @phpstan-return Process<string, string>
     */
    public function invoke(Code $code, ?string $input = null): Process;
}
