<?php

namespace Playground\Process;

use Symfony\Component\Process\Process;

final class SymfonyProcessFactory
{
    /**
     * @param string[] $command The command to run and its arguments listed as separate entries
     * @param ?string $cwd The working directory or null to use the working dir of the current PHP process
     * @param ?array<string,string> $env The environment variables or null to use the same environment as the current PHP process
     * @param ?mixed $input The input as stream resource, scalar or \Traversable, or null for no input
     * @param ?float $timeout The timeout in seconds or null to disable
     * @phpstan-return Process<string,string>
     */
    public function create(array $command, string $cwd = null, array $env = null, $input = null, ?float $timeout = 60.0): Process
    {
        return new Process($command, $cwd, $env, $input, $timeout);
    }
}
