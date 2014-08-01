<?php

namespace Vivait\StringGeneratorBundle\Model;

interface GeneratorInterface
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