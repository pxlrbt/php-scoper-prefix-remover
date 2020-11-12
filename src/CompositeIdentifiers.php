<?php

namespace pxlrbt\PhpScoper\PrefixRemover;

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


    public function __construct(iterable $identifiersList)
    {
        $this->identifiersList = $identifiersList;
    }

    public function getFunctions(): iterable
    {
        $this->_ensureIndex();
        return $this->functions;
    }

    public function getClasses(): iterable
    {
        $this->_ensureIndex();
        return $this->classes;
    }

    public function getConstants(): iterable
    {
        $this->_ensureIndex();
        return $this->constants;
    }

    public function getAll(): iterable
    {
        yield from $this->getFunctions();
        yield from $this->getClasses();
        yield from $this->getConstants();
    }

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
