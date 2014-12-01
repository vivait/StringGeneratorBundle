<?php

namespace Vivait\StringGeneratorBundle\Model;

interface GeneratorInterface
{
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
