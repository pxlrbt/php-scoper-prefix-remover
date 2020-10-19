<?php

namespace pxlrbt\PhpScoper\PrefixRemover;

use PhpParser\ParserFactory;

class IdentifierExtractor
{
    public function __construct()
    {
        $this->stubFiles = [];
        $this->extractStatements = [
            "PhpParser\Node\Stmt\Class_",
            "PhpParser\Node\Stmt\Interface_",
            "PhpParser\Node\Stmt\Trait_",
            "PhpParser\Node\Stmt\Function_"
        ];
    }

    public function addStub($file)
    {
        $this->stubFiles[] = $file;
        return $this;
    }

    public function extract()
    {
        $identifiers = [];
        foreach ($this->stubFiles as $file) {
            $content = file_get_contents($file);
            $ast = $this->generateAst($content);
            $identifiers = array_merge($identifiers, $this->extractIdentifiers($ast));
        }

        return $identifiers;
    }

    public function generateAst($code)
    {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        return $parser->parse($code);
    }

    public function extractIdentifiers($ast)
    {
        $globals = [];
        $items = $ast;

        while (count($items) > 0) {
            $item = array_pop($items);

            if (isset($item->stmts)) {
                $items = array_merge($items, $item->stmts);
            }

            if (in_array(get_class($item), $this->extractStatements)) {
                $globals[] = $item->name->name;
            }

        }
        // foreach ($ast as $item) {
        //     if (isset($item->stmts)) {
        //         $globals = array_merge($globals, $this->extractIdentifiers($item->stmts));
        //     }

        //     if (in_array(get_class($item), $this->extractStatements)) {
        //         $globals[] = $item->name->name;
        //     }
        // }

        return $globals;
    }
}
