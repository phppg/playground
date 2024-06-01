<?php

declare(strict_types=1);

namespace Playground\Code;

use PhpParser\Node;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinterAbstract as PrettyPrinter;
use Playground\Code;

final class ParsedCode implements Code
{
    /** @var Node\Stmt[] */
    private array $ast;

    public function __construct(
        private PrettyPrinter $pprinter,
        private ParserFactory $factory,
        private Code $source,
    ) {
    }

    public function __toString(): string
    {
        return $this->pprinter->prettyPrintFile($this->getParsedNodes());
    }

    /**
     * @return Node[]
     */
    public function getParsedNodes(): array
    {
        return $this->ast ??= $this->parse($this->factory->createForHostVersion());
    }

    public function getSource(): ?Code
    {
        return $this->source;
    }

    /**
     * @return Node\Stmt[]
     */
    private function parse(Parser $parser): array
    {
        return $parser->parse($this->source->__toString()) ?? [];
    }
}
