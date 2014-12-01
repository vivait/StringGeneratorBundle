<?php

namespace Vivait\StringGeneratorBundle\Generator;

use Symfony\Component\Security\Core\Util\SecureRandom;
use Vivait\StringGeneratorBundle\Model\GeneratorInterface;

class PasswordGenerator implements GeneratorInterface
{
    /**
     * @var SecureRandom
     */
    private $secureRandom;
    private $length;

    /**
     * @param SecureRandom $secureRandom
     */
    public function __construct(SecureRandom $secureRandom)
    {
        $this->secureRandom = $secureRandom;
    }

    /**
     * @param integer $length
     * @return $this
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * @return string
     */
    public function generate()
    {
        return $this->secureRandom->nextBytes($this->length);
    }
}
