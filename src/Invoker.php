<?php

namespace Playground;

use Symfony\Component\Process\Process;

/**
 * PHP code invoker
 */
interface Invoker
{
    /**
     * PHP Invoker
     *
     * @phpstan-return Process<string,string>
     */
    public function invoke(Code $code, ?string $input = null): Process;
}
