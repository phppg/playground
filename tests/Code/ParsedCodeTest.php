<?php

declare(strict_types=1);

namespace Playground\Code;

use PHPUnit\Framework\Attributes\DataProvider;
use PhpParser\Node;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;
use PhpParser\PrettyPrinterAbstract;
use Playground\Code\ParsedCode;
use Playground\Code\SourceCode;
use Playground\Statistics;

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
     * @param array{string:string,ast:Node\Stmt[], stats: array<mixed>} $expected
     */
    #[DataProvider('sourceProvider')]
    public function test(array $expected, string $source): void
    {
        $source_code = new SourceCode($source);
        $subject = new ParsedCode($this->pprinter, $this->factory, $source_code);
        $stats = Statistics::fromCode($subject);

        $this->assertSame($expected['string'], $subject->__toString());
        $this->assertEquals($expected['ast'], $subject->getParsedNodes());
        $this->assertEquals($expected['stats'], $stats->toArray());
    }

    /**
     * @return iterable<array{array{string: string, ast: Node[], stats: array<mixed>}, string}>
     */
    public static function sourceProvider(): iterable
    {
        return [
            'returns only <?php tag from empty input' => [
                [
                    'string' => <<<'PHP'
                        <?php


                        PHP,
                    'ast' => [],
                    'stats' => [
                        'chars' => 7,
                        'lines' => 3,
                        'tokens' => 2,
                        'stmts' => 0,
                        'node_names' => [],
                    ],
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
                                'startTokenPos' => 3,
                                'startFilePos' => 11,
                                'endTokenPos' => 3,
                                'endFilePos' => 15,
                                'rawValue' => '"foo"',
                            ])], [
                                'startTokenPos' => 1,
                                'startFilePos' => 6,
                                'endTokenPos' => 5,
                                'endFilePos' => 18,
                                'startLine' => 1,
                                'endLine' => 1,
                            ])
                    ],
                    'stats' => [
                        'chars' => 18,
                        'lines' => 3,
                        'tokens' => 6,
                        'stmts' => 1,
                        'node_names' => [
                            Node\Scalar\String_::class,
                            Node\Stmt\Echo_::class,
                        ],
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
                                'startTokenPos' => 2,
                                'startFilePos' => 4,
                                'endTokenPos' => 2,
                                'endFilePos' => 8,
                                'rawValue' => '"foo"',
                            ])], [
                                'startLine' => 1,
                                'endLine' => 1,
                                'startTokenPos' => 0,
                                'startFilePos' => 0,
                                'endTokenPos' => 4,
                                'endFilePos' => 11,
                            ])
                    ],
                    'stats' => [
                        'chars' => 18,
                        'lines' => 3,
                        'tokens' => 6,
                        'stmts' => 1,
                        'node_names' => [
                            Node\Scalar\String_::class,
                            Node\Stmt\Echo_::class,
                        ],
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
                            'startTokenPos' => 0,
                            'startFilePos' => 0,
                            'endTokenPos' => 0,
                            'endFilePos' => 2,
                        ]),
                    ],
                    'stats' => [
                        'chars' => 3,
                        'lines' => 1,
                        'tokens' => 1,
                        'stmts' => 1,
                        'node_names' => [
                            Node\Stmt\InlineHTML::class
                        ],
                    ],
                ],
                'foo'
            ],
            'returns nested if stmts' => [
                [
                    'string' => <<<'PHP'
                        <?php

                        if (true) {
                            if (true) {
                                if (true) {
                                    echo PHP_EOL;
                                }
                            }
                        }
                        PHP,
                    'ast' => [
                        new Node\Stmt\If_(
                            new Node\Expr\ConstFetch(
                                new Node\Name('true', [
                                    'startLine' => 1,
                                    'endLine' => 1,
                                    'startTokenPos' => 4,
                                    'startFilePos' => 10,
                                    'endTokenPos' => 4,
                                    'endFilePos' => 13,
                                ]),
                                attributes: [
                                    'startLine' => 1,
                                    'endLine' => 1,
                                    'startTokenPos' => 4,
                                    'startFilePos' => 10,
                                    'endTokenPos' => 4,
                                    'endFilePos' => 13,
                                ],
                            ),
                            subNodes: [
                                'stmts' => [
                                    new Node\Stmt\If_(
                                        new Node\Expr\ConstFetch(
                                            new Node\Name('true', [
                                                'startLine' => 1,
                                                'endLine' => 1,
                                                'startTokenPos' => 10,
                                                'startFilePos' => 20,
                                                'endTokenPos' => 10,
                                                'endFilePos' => 23,
                                            ]),
                                            attributes: [
                                                'startLine' => 1,
                                                'endLine' => 1,
                                                'startTokenPos' => 10,
                                                'startFilePos' => 20,
                                                'endTokenPos' => 10,
                                                'endFilePos' => 23,
                                            ],
                                        ),
                                        [
                                            'stmts' => [
                                                new Node\Stmt\If_(
                                                    new Node\Expr\ConstFetch(
                                                        new Node\Name('true', [
                                                            'startLine' => 1,
                                                            'endLine' => 1,
                                                            'startTokenPos' => 16,
                                                            'startFilePos' => 30,
                                                            'endTokenPos' => 16,
                                                            'endFilePos' => 33,
                                                        ]),
                                                        attributes: [
                                                            'startLine' => 1,
                                                            'endLine' => 1,
                                                            'startTokenPos' => 16,
                                                            'startFilePos' => 30,
                                                            'endTokenPos' => 16,
                                                            'endFilePos' => 33,
                                                        ]
                                                    ),
                                                    [
                                                        'stmts' => [
                                                            new Node\Stmt\Echo_(
                                                                [
                                                                    new Node\Expr\ConstFetch(
                                                                        new Node\Name('PHP_EOL', [
                                                                            'startLine' => 1,
                                                                            'endLine' => 1,
                                                                            'startTokenPos' => 21,
                                                                            'startFilePos' => 41,
                                                                            'endTokenPos' => 21,
                                                                            'endFilePos' => 47,
                                                                        ]),
                                                                        attributes: [
                                                                            'startLine' => 1,
                                                                            'endLine' => 1,
                                                                            'startTokenPos' => 21,
                                                                            'startFilePos' => 41,
                                                                            'endTokenPos' => 21,
                                                                            'endFilePos' => 47,
                                                                        ],
                                                                    ),
                                                                ],
                                                                attributes: [
                                                                    'startLine' => 1,
                                                                    'endLine' => 1,
                                                                    'startTokenPos' => 19,
                                                                    'startFilePos' => 36,
                                                                    'endTokenPos' => 22,
                                                                    'endFilePos' => 48,
                                                                ],
                                                            ),

                                                        ]
                                                    ],
                                                    [
                                                        'startLine' => 1,
                                                        'endLine' => 1,
                                                        'startTokenPos' => 13,
                                                        'startFilePos' => 26,
                                                        'endTokenPos' => 22,
                                                        'endFilePos' => 48,
                                                    ],
                                                ),

                                            ]
                                        ],
                                        [
                                            'startLine' => 1,
                                            'endLine' => 1,
                                            'startTokenPos' => 7,
                                            'startFilePos' => 16,
                                            'endTokenPos' => 22,
                                            'endFilePos' => 48,
                                        ],
                                    ),

                                ]
                            ],
                            attributes: [
                                'startLine' => 1,
                                'endLine' => 1,
                                'startTokenPos' => 1,
                                'startFilePos' => 6,
                                'endTokenPos' => 22,
                                'endFilePos' => 48,
                            ],
                        ),
                    ],
                    'stats' => [
                        'chars' => 98,
                        'lines' => 9,
                        'tokens' => 36,
                        'stmts' => 4,
                        'node_names' => [
                            Node\Expr\ConstFetch::class,
                            Node\Name::class,
                            Node\Stmt\Echo_::class,
                            Node\Stmt\If_::class,
                        ],
                    ],
                ],
                '<?php if (true) if (true) if (true) echo PHP_EOL;'
            ],
        ];
    }
}
