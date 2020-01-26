<?php

declare(strict_types=1);

namespace Playground\Invoker;

use Playground\Code;
use Playground\CommandBuilder;
use Playground\File;
use Playground\Process\SymfonyProcessFactory as ProcessFactory;
use Playground\Invoker as InvokerInterface;
use Symfony\Component\Console\Input\InputInterface as Input;
use Symfony\Component\Process\Process;

final class ProcessInvoker implements InvokerInterface
{
    private CommandBuilder $cmd_builder;
    private string $cwd;
    /** @var array<string,string> */
    private array $env;
    private File $file;
    private ProcessFactory $proc_factory;

    /**
     * @param array<string,string> $env
     */
    public function __construct(
        CommandBuilder $cmd_builder,
        string $cwd,
        array $env,
        File $file,
        ProcessFactory $proc_factory
    ) {
        $this->cmd_builder = $cmd_builder;
        $this->cwd = $cwd;
        $this->env = $env;
        $this->file = $file;
        $this->proc_factory = $proc_factory;
    }

    /**
     * @phpstan-return Process<string,string>
     */
    public function process(Code $code, Input $input = null): Process
    {
        $cmd = $this->cmd_builder->build($this->file->getPath());
        $proc = $this->proc_factory->create($cmd);

        return $proc;
    }

    public function invoke(Code $code, Input $input = null): Process
    {
        $proc = $this->process($code, $input);

        $this->file->write($code);

        $proc->mustRun();

        return $proc;
    }
}
