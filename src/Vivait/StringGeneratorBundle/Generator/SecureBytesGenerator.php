<?php

namespace Vivait\StringGeneratorBundle\Generator;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Util\SecureRandom;
use Vivait\StringGeneratorBundle\Model\ConfigurableGeneratorInterface;

class SecureBytesGenerator implements ConfigurableGeneratorInterface
{
    /**
     * @var SecureRandom
     */
    private $secureRandom;
    private $length = 8;

    /**
     * @param SecureRandom $secureRandom
     */
    public function __construct(SecureRandom $secureRandom)
    {
        $this->secureRandom = $secureRandom;
    }

    /**
     * @param integer $length
     * @return $this
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * @return string
     */
    public function generate()
    {
        return $this->secureRandom->nextBytes($this->length);
    }

    /**
     * @param OptionsResolver $resolver
     * @return mixed
     */
    public function getDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'length' => $this->length,
            ]);
    }

    /**
     * @param array $options
     * @return mixed
     */
    public function setOptions(array $options)
    {
        $this->length = $options['length'];
    }
}
