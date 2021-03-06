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
use Playground\Statistics;
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
        $stats = Statistics::fromCode($subject);

        $this->assertSame($expected['string'], $subject->__toString());
        $this->assertEquals($expected['ast'], $subject->getParsedNodes());
        $this->assertEquals($expected['stats'] ?? [], $stats->toArray());
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
                            ])], [
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
                            ])], [
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
                                ]),
                                [
                                    'startLine' => 1,
                                    'endLine' => 1,
                                ]
                            ),
                            [
                                'stmts' => [
                                    new Node\Stmt\If_(
                                        new Node\Expr\ConstFetch(
                                            new Node\Name('true', [
                                                'startLine' => 1,
                                                'endLine' => 1,
                                            ]),
                                            [
                                                'startLine' => 1,
                                                'endLine' => 1,
                                            ]
                                        ),
                                        [
                                            'stmts' => [
                                                new Node\Stmt\If_(
                                                    new Node\Expr\ConstFetch(
                                                        new Node\Name('true', [
                                                            'startLine' => 1,
                                                            'endLine' => 1,
                                                        ]),
                                                        [
                                                            'startLine' => 1,
                                                            'endLine' => 1,
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
                                                                        ]),
                                                                        [
                                                                            'startLine' => 1,
                                                                            'endLine' => 1,
                                                                        ]
                                                                    ),
                                                                ],
                                                                [
                                                                    'startLine' => 1,
                                                                    'endLine' => 1,
                                                                ]
                                                            ),

                                                        ]
                                                    ],
                                                    [
                                                        'startLine' => 1,
                                                        'endLine' => 1,
                                                    ]
                                                ),

                                            ]
                                        ],
                                        [
                                            'startLine' => 1,
                                            'endLine' => 1,
                                        ]
                                    ),

                                ]
                            ],
                            [
                                'startLine' => 1,
                                'endLine' => 1,
                            ]
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
