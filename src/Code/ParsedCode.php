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
    private PrettyPrinter $pprinter;
    private ParserFactory $factory;
    private Code $source;
    /** @var Node\Stmt[] */
    private array $ast;

    public function __construct(PrettyPrinter $pprinter, ParserFactory $factory, Code $source)
    {
        $this->pprinter = $pprinter;
        $this->factory = $factory;
        $this->source = $source;
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
        if (!isset($this->ast)) {
            $this->ast = $this->parse($this->factory->create(ParserFactory::ONLY_PHP7));
        }

        return $this->ast;
    }

    /**
     * @return Node\Stmt[]
     */
    private function parse(Parser $parser): array
    {
        return $parser->parse($this->source->__toString()) ?? [];
    }
}
