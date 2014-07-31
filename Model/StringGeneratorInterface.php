<?php

namespace Vivait\StringGeneratorBundle\Model;

interface StringGeneratorInterface
{

    /**
     * @param $alphabet
     * @return $this
     */
    public function setAlphabet($alphabet);

    /**
     * @param $length
     * @return $this
     */
    public function setLength($length);

    /**
     * @return string
     */
    public function generate();
} 