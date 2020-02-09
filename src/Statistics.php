<?php

namespace Playground;

use function count;
use function explode;
use function mb_strlen;
use function token_get_all;
use OutOfRangeException;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use Playground\Code\ParsedCode;

/**
 * @property int $lines
 */
final class Statistics
{
    public int $chars;
    public int $lines;
    public int $tokens;
    public int $stmts;

    private function __construct()
    {
        // noop
    }

    /**
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->$name;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value): void
    {
        throw new OutOfRangeException(sprintf('Undefined property: %s::%s', __CLASS__, $name));
    }

    /**
     * @pararm Node[] $nodes
     */
    public static function countStmts(array $nodes): int
    {
        $stmt_counter = new StmtCounter;
        $traverser = new NodeTraverser();
        $traverser->addVisitor($stmt_counter);
        $traverser->traverse($nodes);

        return $stmt_counter->getCount();
    }

    public static function fromCode(ParsedCode $code): self
    {
        $source_code = (string)$code;

        $stats = new self();
        $stats->chars = mb_strlen($source_code, 'UTF-8');
        $stats->lines = count(explode("\n", $source_code));
        $stats->tokens = count(token_get_all($source_code));
        $stats->stmts = self::countStmts($code->getParsedNodes());

        return $stats;
    }

    public function toArray(): array
    {
        return [
            'chars' => $this->chars,
            'lines' => $this->lines,
            'tokens' => $this->tokens,
            'stmts' => $this->stmts,
        ];
    }
}
