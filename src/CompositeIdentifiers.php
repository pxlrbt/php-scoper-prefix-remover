<?php

namespace pxlrbt\PhpScoper\PrefixRemover;

/**
 * An identifier collection that aggregates identifiers from multiple other collections.
 */
class CompositeIdentifiers implements IdentifiersInterface
{
    /**
     * @var iterable<IdentifiersInterface>
     */
    protected $identifiersList;
    protected $functions;
    protected $classes;
    protected $constants;
    protected $all;

    /**
     * @param iterable<IdentifiersInterface> $identifiersList
     */
    public function __construct(iterable $identifiersList)
    {
        $this->identifiersList = $identifiersList;
    }

    /**
     * @inheritDoc
     */
    public function getFunctions(): iterable
    {
        $this->_ensureIndex();
        return $this->functions;
    }

    /**
     * @inheritDoc
     */
    public function getClasses(): iterable
    {
        $this->_ensureIndex();
        return $this->classes;
    }

    /**
     * @inheritDoc
     */
    public function getConstants(): iterable
    {
        $this->_ensureIndex();
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

    /**
     * Merges symbols from all internal identifier collections, and caches them.
     *
     * The symbols are accessible via the `$functions`, `$classes`, and `$constants` properties afterwards.
     */
    protected function _ensureIndex()
    {
        if (is_iterable($this->functions) && is_iterable($this->classes) && is_iterable($this->all)) {
            return;
        }

        foreach ($this->identifiersList as $identifiers) {
            /* @var $identifiers IdentifiersInterface */
            $this->functions = $this->appendList($this->functions, $identifiers->getFunctions());
            $this->classes = $this->appendList($this->classes, $identifiers->getClasses());
            $this->constants = $this->appendList($this->constants, $identifiers->getConstants());
        }
    }

    /**
     * Appends a list to the end of another list.
     *
     * @param iterable $toList A list to append to.
     * @param iterable $sourceList A list to append.
     *
     * @return iterable<iterable> A list with all items from the given lists. Duplicates possible.
     */
    protected function appendList(iterable $toList, iterable $sourceList): iterable
    {
        yield from $toList;
        yield from $sourceList;
    }
}
