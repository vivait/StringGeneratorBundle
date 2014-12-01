<?php

namespace Vivait\StringGeneratorBundle\Generator;

use Vivait\StringGeneratorBundle\Model\GeneratorInterface;

class StringGenerator implements GeneratorInterface
{

    /**
     * @var
     */
    private $length;

    /**
     * @var string
     */
    private $chars;

    public function __construct()
    {
        $this->chars = 'abcdefjhijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ12345567890';
    }

    /**
     * @param string $chars
     */
    public function setChars($chars)
    {
        if(!$chars){
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
        $this->length = $length;
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

        return implode($str);
    }
}
