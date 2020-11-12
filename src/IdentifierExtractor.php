<?php

namespace pxlrbt\PhpScoper\PrefixRemover;

use PhpParser\ParserFactory;

class IdentifierExtractor
{
    public function __construct($statements = null)
    {
        $this->stubFiles = [];
        $this->extractStatements = $statements ?? [
            'classes' => [
                "Stmt_Class",
                "Stmt_Interface",
                "Stmt_Trait",
            ],
            'functions' => [
                "Stmt_Function"
            ],
            'constants' => [
                'Stmt_Const'
            ],
        ];
    }

    public function addStub($file)
    {
        $this->stubFiles[] = $file;
        return $this;
    }

    public function extract(): IdentifiersInterface
    {
        $identifiers = [];
        foreach ($this->stubFiles as $file) {
            $content = file_get_contents($file);
            $ast = $this->generateAst($content);
            $identifiers[] = $this->extractIdentifiersFromAst($ast);
        }

        return new CompositeIdentifiers($identifiers);
    }

    protected function generateAst($code)
    {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        return $parser->parse($code);
    }

    protected function extractIdentifiersFromAst($ast): IdentifiersInterface
    {
        $identifiers = (object) [];
        foreach (array_keys($this->extractStatements) as $category) {
            $identifiers->{$category} = [];
        }

        $items = $ast;

        while (count($items) > 0) {
            $item = array_pop($items);

            if (isset($item->stmts)) {
                $items = array_merge($items, $item->stmts);
            }

            foreach ($this->extractStatements as $category => $types) {
                if (in_array($item->getType(), $this->extractStatements)) {
                    $identifiers->{$category}[] = $item->name;
                }
            }
        }

        $identifiers = new Identifiers($identifiers->functions, $identifiers->classes, $identifiers->constants);

        return $identifiers;
    }
}
