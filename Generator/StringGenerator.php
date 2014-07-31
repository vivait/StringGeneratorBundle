<?php

namespace Vivait\StringGeneratorBundle\Generator;

use Vivait\StringGeneratorBundle\Model\StringGeneratorInterface;

class StringGenerator implements StringGeneratorInterface
{

    private $alphabet;
    private $length;

    public function setAlphabet($alphabet)
    {
        $this->alphabet = $alphabet;
        return $this;
    }

    public function setLength($length)
    {
        $this->length = $length;
        return $this;
    }

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