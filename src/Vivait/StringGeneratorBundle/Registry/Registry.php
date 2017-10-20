<?php

namespace Vivait\StringGeneratorBundle\Registry;

use Vivait\StringGeneratorBundle\Model\GeneratorInterface;

class Registry
{

    /**
     * @var array
     */
    private $generators;

    /**
     * @param array $legacyGenerators
     */
    public function __construct(array $legacyGenerators = [])
    {
        $this->generators = $legacyGenerators;
    }

    /**
     * @param GeneratorInterface $generator
     * @param string             $alias
     */
    public function addGenerator(GeneratorInterface $generator, $alias)
    {
        if (array_key_exists($alias, $this->generators)) {
            throw new \InvalidArgumentException("The alias {$alias} is already a registered Generator.");
        }

        $this->generators[$alias] = $generator;
    }

    /**
     * @param string $field
     *
     * @throws \OutOfBoundsException
     *
     * @return GeneratorInterface
     */
    public function get($field)
    {
        if (isset($this->generators[$field])) {
            return $this->generators[$field];
        }

        throw new \OutOfBoundsException(sprintf('Field "%s" not found in registry', $field));
    }
}
