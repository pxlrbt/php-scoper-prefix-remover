<?php

namespace pxlrbt\PhpScoper\PrefixRemover;

/**
 * A collection of PHP identifiers of different types.
 */
interface IdentifiersInterface
{

    /**
     * Retrieves a list of function identifiers.
     *
     * @return iterable<string> A list of function FQNs.
     */
    public function getFunctions(): iterable;

    /**
     * Retrieves a list of class identifiers.
     *
     * @return iterable<string> A list of class FQNs. This includes classes, interfaces, and traits.
     */
    public function getClasses(): iterable;

    /**
     * Retrieves a list of constant identifiers.
     *
     * @return iterable<string> A list of constant FQNs.
     */
    public function getConstants(): iterable;

    /**
     * Retrieves a list of all identifiers.
     *
     * @return iterable<string> A list of identifiers for functions, classes, and constants together.
     */
    public function getAll(): iterable;
}
