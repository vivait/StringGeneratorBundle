<?php

namespace Vivait\StringGeneratorBundle\Model;

interface GeneratorInterface
{

    /**
     * @param string $alphabet
     * @return $this
     */
    public function setAlphabet($alphabet);

    /**
     * @param integer $length
     * @return $this
     */
    public function setLength($length);

    /**
     * @return string
     */
    public function generate();
}
