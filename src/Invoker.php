<?php

namespace Playground;

use Symfony\Component\Console\Input\InputInterface as Input;
use Symfony\Component\Console\Output\OutputInterface as Output;
use Symfony\Component\Process\Process;

/**
 * PHP code invoker
 */
interface Invoker
{
    /**
     * PHP Invoker
     *
     * @return Process<string,string>
     */
    public function invoke(Code $code, Input $input = null): Process;
}
