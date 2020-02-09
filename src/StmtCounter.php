<?php

declare(strict_types=1);

namespace Playground;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\NodeVisitorAbstract;

final class StmtCounter extends NodeVisitorAbstract
{
    private int $count = 0;

    /**
     * @return void
     */
    public function enterNode(Node $node)
    {
        if ($node instanceof Stmt) {
            $this->count++;
        }
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
