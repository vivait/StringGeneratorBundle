<?php

namespace Vivait\StringGeneratorBundle\Generator;

use Vivait\StringGeneratorBundle\Model\GeneratorInterface;

class StringGenerator implements GeneratorInterface
{

    /**
     * @var
     */
    private $length = 8;

    /**
     * @var string
     */
    private $chars = 'abcdefjhijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ12345567890';
    private $prefix = '';

    /**
     * @param string $chars
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
     * @return $this
     */
    public function setLength($length)
    {
        if ($length < 1) {
            throw new \OutOfBoundsException('Length must be greater than 0');
        }

        $this->length = $length;
        return $this;
    }

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
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
     */
    public function setOptions(array $options)
    {
        // TODO: Implement setOptions() method.
    }
}
