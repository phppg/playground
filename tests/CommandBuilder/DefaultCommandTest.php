<?php

declare(strict_types=1);

namespace Playground\CommandBuilder;

use Playground\CommandBuilder\DefaultCommand;

final class DefaultCommandTest extends \Playground\TestCase
{
    /**
     * @dataProvider argsProvider
     */
    public function test(array $expected, array $options): void
    {
        $subject = new DefaultCommand(...$options);

        $this->assertSame($expected, $subject->build(['file' => 'file.php']));
    }

    public function argsProvider(): array
    {
        return [
            [
                ['php', '-f', 'file.php'],
                ['php'],
            ],
            [
                ['php', '-f', 'file.php'],
                ['php', null],
            ],
            [
                ['php', '-c', 'php.ini', '-f', 'file.php'],
                ['php', 'php.ini'],
            ],
        ];
    }
}
