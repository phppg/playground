<?php

declare(strict_types=1);

namespace Playground\Invoker;

use Playground\Code\SourceCode;
use Playground\CommandBuilder;
use Playground\CommandBuilder\DefaultCommand;
use Playground\File;
use Playground\Process\SymfonyProcessFactory;
use function Safe\mkdir;
use function Safe\tempnam;

final class ProcessInvokerTest extends \Playground\TestCase
{
    private const TMP_DIR = __DIR__ . '/tmp';

    private DefaultCommand $cmd_builder;
    private SymfonyProcessFactory $proc_factory;
    private File $tmp_file;

    public static function setUpBeforeClass(): void
    {
        is_dir(self::TMP_DIR) or mkdir(self::TMP_DIR);
    }

    public function setUp(): void
    {
        $this->tmp_file = new File(tempnam(self::TMP_DIR, __CLASS__));
        $this->proc_factory = new SymfonyProcessFactory();
        $this->cmd_builder = new DefaultCommand(['name' => 'php', 'noconf' => true]);
    }

    public function test(): void
    {
        $subject = new ProcessInvoker(
            $this->cmd_builder,
            self::TMP_DIR,
            [],
            $this->tmp_file,
            $this->proc_factory,
        );

        $source = new SourceCode('<?= PHP_VERSION ?>');
        $actual = $subject->invoke($source);

        $this->assertSame(PHP_VERSION, $actual->getOutput());
    }
}
