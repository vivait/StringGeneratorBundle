<?php

namespace Vivait\StringGeneratorBundle\Registry;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Vivait\StringGeneratorBundle\Model\GeneratorInterface;

class Registry
{
    /**
     * @var array
     */
    private $generators;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     * @param array $generators
     */
    function __construct(ContainerInterface $container, array $generators = [])
    {
        $this->container = $container;
        $this->addAll($generators);
    }

    /**
     * @param $field
     * @return GeneratorInterface
     * @throws \OutOfBoundsException
     */
    public function get($field)
    {
        if (isset($this->generators[$field])) {
            return $this->generators[$field];
        }

        throw new \OutOfBoundsException(sprintf('Field "%s" not found in registry', $field));
    }

    /**
     * @param $field
     * @param $class
     * @return $this
     */
    public function add($field, $class)
    {
        $this->generators[$field] = $this->resolveGeneratorType($class);
        return $this;
    }

    /**
     * @param array $generators
     * @return $this
     */
    public function addAll(array $generators)
    {
        foreach ($generators as $field => $class) {
            $this->add($field, $class);
        }
        return $this;
    }

    /**
     * @param $class
     * @return GeneratorInterface
     */
    private function resolveGeneratorType($class)
    {
        if ($class instanceof GeneratorInterface) {
            return $class;
        } elseif (($service = $this->container->get($class, ContainerInterface::NULL_ON_INVALID_REFERENCE))) {
            return $service;
        } elseif (is_a($class, 'Vivait\StringGeneratorBundle\Generator\GeneratorInterface', true)) {
            return new $class;
        }

        throw new \InvalidArgumentException('Invalid Generator');
    }
}
