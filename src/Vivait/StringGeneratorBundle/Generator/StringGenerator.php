<?php

namespace Vivait\StringGeneratorBundle\Generator;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Vivait\StringGeneratorBundle\Model\ConfigurableGeneratorInterface;

class StringGenerator implements ConfigurableGeneratorInterface
{

    /**
     * @var
     */
    private $length = 8;

    /**
     * @var string
     */
    private $chars = 'abcdefjhijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ12345567890';

    /**
     * @var string
     */
    private $prefix = '';

    /**
     * @param string $chars
     * @deprecated options should be used instead
     */
    public function setChars($chars)
    {
        if(!is_string($chars) || $chars === ''){
            throw new \OutOfBoundsException('$chars cannot be empty');
        }

        $this->chars = $chars;
    }

    /**
     * Set the length of the generated string
     * @param $length
     * @return ConfigurableGeneratorInterface
     */
    public function setLength($length)
    {
        $this->length = $length;
        return $this;
    }

    /**
     * @param $prefix
     * @return ConfigurableGeneratorInterface
     * @deprecated options should be used instead
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * Creates a random string based on a length and alphabet
     *
     * @return string
     */
    public function generate()
    {
        $str = [];
        $alphaLength = strlen($this->chars) - 1;
        for ($i = 0; $i < $this->length; $i++) {
            $n = rand(0, $alphaLength);
            $str[] = $this->chars[$n];
        }

        return $this->prefix . implode($str);
    }

    /**
     * @param array $options
     * @return mixed|void
     */
    public function setOptions(array $options)
    {
        $this->chars = $options['chars'];
        $this->length = $options['length'];
        $this->prefix = $options['prefix'];
    }

    /**
     * @param OptionsResolver $resolver
     * @return mixed
     */
    public function getDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                'chars' => $this->chars,
                'length' => $this->length,
                'prefix' => $this->prefix,
            ]);
    }
}
