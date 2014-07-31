<?php

namespace Vivait\StringGeneratorBundle\Model;

interface StringGeneratorInterface {

    /**
     * Creates a random string
     *
     * @return string
     */
    public static function generate();
} 