<?php

declare(strict_types=1);

namespace Playground\CommandBuilder;

use PHPUnit\Framework\Attributes\DataProvider;
use Playground\CommandBuilder\DefaultCommand;

final class DefaultCommandTest extends \Playground\TestCase
{
    /**
     * @param string[] $expected
     * @param array{name:string,ini?:?string,noconf?:bool} $options
     */
    #[DataProvider('argsProvider')]
    public function test(array $expected, array $options): void
    {
        $subject = new DefaultCommand($options);

        $this->assertSame($expected, $subject->build('file.php'));
    }

    /**
     * @return array<array{0:string[],1:array{name:string,ini?:?string,noconf?:bool}}>
     */
    public static function argsProvider(): array
    {
        return [
            [
                ['php', '-f', 'file.php'],
                [
                    'name' => 'php'
                ],
            ],
            [
                ['php', '-f', 'file.php'],
                [
                    'name' => 'php',
                    'ini' => null,
                ],
            ],
            [
                ['php', '-c', 'php.ini', '-f', 'file.php'],
                [
                    'name' => 'php',
                    'ini' => 'php.ini',
                ],
            ],
            [
                ['php', '-n', '-c', 'php.ini', '-f', 'file.php'],
                [
                    'name' => 'php',
                    'ini' => 'php.ini',
                    'noconf' => true,
                ],
            ],
            [
                ['php', '-n', '-d', 'foo=bar', '-c', 'php.ini', '-f', 'file.php'],
                [
                    'name' => 'php',
                    'ini' => 'php.ini',
                    'noconf' => true,
                    'defines' => [
                        'foo' => 'bar'
                    ],
                ],
            ],
        ];
    }
}
