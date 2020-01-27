<?php

declare(strict_types=1);

namespace Playground\Code;

use PhpParser\NodeDumper;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;
use PhpParser\PrettyPrinterAbstract;
use Playground\Code\ParsedCode;
use Playground\Code\SourceCode;
use Playground\File;
use Playground\Process\SymfonyProcessFactory;

final class ParsedCodeTest extends \Playground\TestCase
{
    private ParserFactory $factory;
    private PrettyPrinterAbstract $pprinter;

    public function setUp(): void
    {
        $this->factory = new ParserFactory();
        $this->pprinter = new PrettyPrinter\Standard;
    }

    /**
     * @dataProvider sourceProvider
     */
    public function test(string $expected, string $source): void
    {
        $subject = new ParsedCode($this->pprinter, $this->factory, new SourceCode($source));

        $this->assertSame($expected, $subject->__toString());
    }

    /**
     * @return array<array{0:string,1:string}>
     */
    public function sourceProvider(): array
    {
        return [
            [
                <<<'PHP'
                <?php

                echo "foo";
                PHP,
                '<?php echo "foo" ?>'
            ],
            [
                <<<'PHP'
                <?php

                echo "foo";
                PHP,
                '<?= "foo" ?>'
            ],
        ];
    }
}
