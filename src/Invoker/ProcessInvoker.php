<?php

declare(strict_types=1);

namespace Playground\Invoker;

use Playground\Code;
use Playground\CommandBuilder;
use Playground\File;
use Playground\Invoker as InvokerInterface;
use Playground\Process\SymfonyProcessFactory as ProcessFactory;
use Symfony\Component\Console\Input\InputInterface as Input;
use Symfony\Component\Process\Process;

final class ProcessInvoker implements InvokerInterface
{
    /**
     * @param array<string,string> $env
     */
    public function __construct(
        private CommandBuilder $cmd_builder,
        private string $cwd,
        private array $env,
        private File $file,
        private ?float $timeout,
        private ProcessFactory $proc_factory,
    ) {
    }

    /**
     * @phpstan-return Process<string,string>
     */
    public function process(Code $code, ?string $input = null): Process
    {
        $cmd = $this->cmd_builder->build($this->file->path);
        $proc = $this->proc_factory->create(
            $cmd,
            $this->cwd,
            $this->env,
            $input,
            $this->timeout,
        );

        return $proc;
    }

    public function invoke(Code $code, ?string $input = null): Process
    {
        $proc = $this->process($code, $input);

        $this->file->write($code);

        $proc->mustRun();

        return $proc;
    }
}
