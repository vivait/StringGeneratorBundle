<?php

namespace Vivait\StringGeneratorBundle\Model;

interface GeneratorInterface
{
    /**
     * @param integer $length
     * @return GeneratorInterface
     * @deprecated this will be deprecated in version 2.0 in favour of using callbacks on the generator. This is due to
     * some generators not actually needing a length - only random string type generators require it.
     */
    public function setLength($length);

    /**
     * @return string
     */
    public function generate();
}
