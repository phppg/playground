<?php

declare(strict_types=1);

namespace Playground;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\NodeVisitorAbstract;
use function count;

final class StmtCounter extends NodeVisitorAbstract
{
    private int $stmt_count = 0;
    /** @var array<string,true> */
    private array $classes = [];

    /**
     * @return Node
     */
    public function enterNode(Node $node)
    {
        $class = get_class($node);
        $this->classes[$class] = true;

        if ($node instanceof Stmt) {
            $this->stmt_count++;
        }

        return $node;
    }

    /**
     * @return string[]
     */
    public function getClasses(): array
    {
        $classes = array_keys($this->classes);

        sort($classes);

        return $classes;
    }

    public function getStmtCount(): int
    {
        return $this->stmt_count;
    }
}
