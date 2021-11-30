<?php

namespace pxlrbt\PhpScoper\PrefixRemover;

use PhpParser\ParserFactory;
use PhpParser\Lexer;

class IdentifierExtractor
{
    protected ?Lexer $lexer = null;
    protected array $stubFiles = [];
    protected array $extractStatements = [];
    
    public function __construct($statements = null)
    {
        $this->extractStatements = $statements ?? [
            "Stmt_Class",
            "Stmt_Interface",
            "Stmt_Trait",
            "Stmt_Function"
        ];
    }

    public function addStub($file)
    {
        $this->stubFiles[] = $file;
        return $this;
    }

    public function setLexer($lexer)
    {
        $this->lexer = $lexer;
        return $this;
    }

    public function extract()
    {
        $identifiers = [];
        foreach ($this->stubFiles as $file) {
            $content = file_get_contents($file);
            $ast = $this->generateAst($content);
            $identifiers = array_merge($identifiers, $this->extractIdentifiersFromAst($ast));
        }

        return $identifiers;
    }

    protected function generateAst($code)
    {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7, $this->lexer);
        return $parser->parse($code);
    }

    protected function extractIdentifiersFromAst($ast)
    {
        $globals = [];
        $items = $ast;

        while (count($items) > 0) {
            $item = array_pop($items);

            if (isset($item->stmts)) {
                $items = array_merge($items, $item->stmts);
            }

            if (in_array($item->getType(), $this->extractStatements)) {
                $globals[] = $item->name;
            }
        }

        return $globals;
    }
}
