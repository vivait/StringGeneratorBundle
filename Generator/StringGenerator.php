<?php

namespace Vivait\StringGeneratorBundle\Generator;

use Vivait\StringGeneratorBundle\Model\GeneratorInterface;

class StringGenerator implements GeneratorInterface
{

    private $alphabet;
    private $length;


    /**
     * Set the characters that can be used to generate the string
     *
     * @param $alphabet
     * @return $this
     */
    public function setAlphabet($alphabet)
    {
        $this->alphabet = $alphabet;
        return $this;
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
        $alphaLength = strlen($this->alphabet) - 1;
        for ($i = 0; $i < $this->length; $i++) {
            $n = rand(0, $alphaLength);
            $str[] = $this->alphabet[$n];
        }

        return implode($str);
    }
}