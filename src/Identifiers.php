<?php

namespace pxlrbt\PhpScoper\PrefixRemover;

/**
 * A collection of PHP identifiers of different types.
 */
class Identifiers implements IdentifiersInterface
{
    protected $functions;
    protected $classes;
    protected $constants;

    public function __construct(
        iterable $functions,
        iterable $classes,
        iterable $constants
    )
    {
        $this->functions = $functions;
        $this->classes = $classes;
        $this->constants = $constants;
    }

    /**
     * @inheritDoc
     */
    public function getFunctions(): iterable
    {
        return $this->functions;
    }

    /**
     * @inheritDoc
     */
    public function getClasses(): iterable
    {
        return $this->classes;
    }

    /**
     * @inheritDoc
     */
    public function getConstants(): iterable
    {
        return $this->constants;
    }

    /**
     * @inheritDoc
     */
    public function getAll(): iterable
    {
        yield from $this->getFunctions();
        yield from $this->getClasses();
        yield from $this->getConstants();
    }
}
