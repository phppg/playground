<?php

declare(strict_types=1);

namespace Playground;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\NodeVisitorAbstract;
use function array_keys;
use function count;
use function sort;

final class StmtCounter extends NodeVisitorAbstract
{
    /** @phpstan-var 0|positive-int */
    private int $stmt_count = 0;
    /** @phpstan-var array<non-falsy-string, true> */
    private array $classes = [];

    public function enterNode(Node $node)
    {
        $this->classes[$node::class] = true;

        if ($node instanceof Stmt) {
            $this->stmt_count++;
        }

        return $node;
    }

    /**
     * @return list<non-falsy-string>
     */
    public function getClasses(): array
    {
        $classes = array_keys($this->classes);

        sort($classes);

        return $classes;
    }

    /**
     * @phpstan-return 0|positive-int
     */
    public function getStmtCount(): int
    {
        return $this->stmt_count;
    }
}
