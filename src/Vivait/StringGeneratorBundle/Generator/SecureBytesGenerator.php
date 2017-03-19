<?php

namespace Vivait\StringGeneratorBundle\Generator;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Vivait\StringGeneratorBundle\Model\ConfigurableGeneratorInterface;

class SecureBytesGenerator implements ConfigurableGeneratorInterface
{
    /**
     * @var SecureRandom
     */
    private $secureRandom;
    private $length = 8;

    /**
     * @param integer $length
     * @return ConfigurableGeneratorInterface
     */
    public function setLength($length)
    {
        $this->length = $length;
        return $this;
    }

    /**
     * @return string
     */
    public function generate()
    {
        return random_bytes($this->length);
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
