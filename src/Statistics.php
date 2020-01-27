<?php

namespace Playground;

use OutOfRangeException;
use Playground\Code\ParsedCode;
use function count;
use function explode;

/**
 * @property int $lines
 */
final class Statistics
{
    private int $lines;

    private function __construct()
    {
        // noop
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        throw new OutOfRangeException(sprintf('Undefined property: %s::%s', __CLASS__, $name));
    }

    public static function fromCode(ParsedCode $code): self
    {
        $source_code = (string)$code;

        $stats = new self();
        $stats->lines = count(explode("\n", $source_code));
        $stats->characters = mb_strlen($source_code, 'UTF-8');

        return $stats;
    }
}
