<?php

namespace spec\Vivait\StringGeneratorBundle\Generator;

use PhpSpec\ObjectBehavior;

class StringGeneratorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Vivait\StringGeneratorBundle\Generator\StringGenerator');
    }

    function it_generates_a_string_based_on_length()
    {
        $this->setLength(10);
        $this->generate()->shouldBeString();
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