<?php

namespace spec\Vivait\StringGeneratorBundle\Generator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SecureBytesGeneratorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Vivait\StringGeneratorBundle\Generator\SecureBytesGenerator');
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
