<?php

namespace Vivait\StringGeneratorBundle\Generator;

use Ramsey\Uuid\Uuid;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vivait\StringGeneratorBundle\Model\ConfigurableGeneratorInterface;

class UuidGenerator implements ConfigurableGeneratorInterface
{
    /**
     * @var integer
     */
    private $version;

    /**
     * @var string
     */
    private $namespace;

    /**
     * Creates a random string based on a length and alphabet
     *
     * @return string
     */
    public function generate()
    {
        switch($this->version) {
            case 1:
                return Uuid::uuid1()->toString();

            case 3:
                $this->checkNamespace($this->version);
                return Uuid::uuid3(Uuid::NAMESPACE_DNS, $this->namespace)->toString();

            case 4:
                return Uuid::uuid4()->toString();

            case 5:
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
}
