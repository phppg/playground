<?php

declare(strict_types=1);

namespace Playground\Code;

use PhpParser\Node;
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
        $this->pprinter = new PrettyPrinter\Standard();
    }

    /**
     * @dataProvider sourceProvider
     * @param array{string:string,ast:Node\Stmt[]} $expected
     */
    public function test(array $expected, string $source): void
    {
        $source_code = new SourceCode($source);
        $subject = new ParsedCode($this->pprinter, $this->factory, $source_code);

        $this->assertSame($expected['string'], $subject->__toString());
        $this->assertEquals($expected['ast'], $subject->getParsedNodes());
    }

    /**
     * @return array<array{0:array{string:string,ast:Node[]},1:string}>
     */
    public function sourceProvider(): array
    {
        return [
            'returns only <?php tag from empty input' => [
                [
                    'string' => <<<'PHP'
                        <?php


                        PHP,
                    'ast' => [],
                ],
                ''
            ],
            'returns only echo from single line' => [
                [
                    'string' => <<<'PHP'
                        <?php

                        echo "foo";
                        PHP,
                    'ast' => [
                        new Node\Stmt\Echo_([
                            new Node\Scalar\String_('foo', [
                                'startLine' => 1,
                                'endLine' => 1,
                                'kind' => 2,
                            ])], [
                                'startLine' => 1,
                                'endLine' => 1,
                            ])
                    ],
                ],
                '<?php echo "foo" ?>'
            ],
            'returns only echo from <?=' => [
                [
                    'string' => <<<'PHP'
                        <?php

                        echo "foo";
                        PHP,
                    'ast' => [
                        new Node\Stmt\Echo_([
                            new Node\Scalar\String_('foo', [
                                'startLine' => 1,
                                'endLine' => 1,
                                'kind' => 2,
                            ])], [
                                'startLine' => 1,
                                'endLine' => 1,
                            ])
                    ],
                ],
                '<?= "foo" ?>'
            ],
            'returns only inline html' => [
                [
                    'string' => <<<'PHP'
                        foo
                        PHP,
                    'ast' => [
                        new Node\Stmt\InlineHTML('foo', [
                                'startLine' => 1,
                                'endLine' => 1,
                                'hasLeadingNewline' => true,
                        ]),
                    ],
                ],
                'foo'
            ],
        ];
    }
}
