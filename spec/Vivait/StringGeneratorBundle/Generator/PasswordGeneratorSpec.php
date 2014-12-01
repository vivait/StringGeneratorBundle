<?php

namespace spec\Vivait\StringGeneratorBundle\Generator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Security\Core\Util\SecureRandom;

class PasswordGeneratorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Vivait\StringGeneratorBundle\Generator\PasswordGenerator');
    }

    function let(SecureRandom $secureRandom)
    {
        $this->beConstructedWith($secureRandom);
    }

    function it_generates_random_password()
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
