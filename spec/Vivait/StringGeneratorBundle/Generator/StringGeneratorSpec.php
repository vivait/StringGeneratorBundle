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

    function it_should_throw_an_exception_if_it_has_no_chars()
    {
        $this->shouldThrow('\OutOfBoundsException')->duringSetChars('');
        $this->shouldThrow('\OutOfBoundsException')->duringSetChars(null);
        $this->shouldThrow('\OutOfBoundsException')->duringSetChars(true);
    }

    function it_should_error_if_its_length_is_set_to_less_than_1()
    {
        $this->shouldThrow('\OutOfBoundsException')->duringSetLength(0);
        $this->shouldThrow('\OutOfBoundsException')->duringSetLength(-1);
        $this->shouldThrow('\OutOfBoundsException')->duringSetLength("ten");
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