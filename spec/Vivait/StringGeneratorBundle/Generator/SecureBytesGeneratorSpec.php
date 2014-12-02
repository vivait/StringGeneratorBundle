<?php

namespace spec\Vivait\StringGeneratorBundle\Generator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Security\Core\Util\SecureRandom;

class SecureBytesGeneratorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Vivait\StringGeneratorBundle\Generator\SecureBytesGenerator');
    }

    function let()
    {
        $secureRandom = new SecureRandom();
        $this->beConstructedWith($secureRandom);
    }

    function it_generates_random_string()
    {
        $this->setLength(10);
        $this->generate()->shouldHaveStrlen(10);
    }

    function getMatchers()
    {
        return [
            'haveStrlen' => function($string, $length){
                return strlen($string) == $length;
            }
        ];
    }
}
