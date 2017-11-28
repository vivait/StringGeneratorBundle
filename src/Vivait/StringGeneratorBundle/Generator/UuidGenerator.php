<?php

namespace Vivait\StringGeneratorBundle\Generator;

use Ramsey\Uuid\Uuid;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vivait\StringGeneratorBundle\Model\ConfigurableGeneratorInterface;

class UuidGenerator implements ConfigurableGeneratorInterface
{
    /**
     * @var Uuid
     */
    private $uuid;

    /**
     * @var integer
     */
    private $version;

    /**
     * @var string
     */
    private $namespace;

    /**
     * Constructor.
     */
    public function __construct()
    {

    }

    /**
     * Creates a random string based on a length and alphabet
     *
     * @return string
     */
    public function generate()
    {
        if(!class_exists('Ramsey\Uuid\Uuid')) {
            throw new \RuntimeException('For use the UUID generator you should setup the ramsey/uuid package');
        }

        switch($this->version) {
            case 1:
                return Uuid::uuid1()->toString();

            case 3:
                $this->checkNamespace($this->version);
                return Uuid::uuid3(Uuid::NAMESPACE_DNS, $this->namespace)->toString();

            case 4:
                return Uuid::uuid4()->toString();

            case 5:
                $this->checkNamespace($this->version);
                return Uuid::uuid5(Uuid::NAMESPACE_DNS, $this->namespace)->toString();

            default:
                throw new \RuntimeException(sprintf('The version %s of UUID does not exists',$this->version));
        }
    }

    /**
     * @param integer $version
     */
    private function checkNamespace($version)
    {
        if(null === $this->namespace) {
            throw new \RuntimeException(sprintf('The version %s of UUID needs a namespace', $version));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        $this->version   = $options['version'];
        $this->namespace = $options['namespace'];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'version'   => 4,
            'namespace' => null
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function setLength($length)
    {
        return null;
    }

    /**
     * @param $version
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @param $namespace
     * @return $this
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }
}
