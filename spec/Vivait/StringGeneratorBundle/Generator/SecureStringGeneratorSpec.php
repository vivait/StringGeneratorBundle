<?php

namespace spec\Vivait\StringGeneratorBundle\Generator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use RandomLib\Factory;
use RandomLib\Generator;
use RandomLib\Mixer;
use SecurityLib\Strength;

class SecureStringGeneratorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Vivait\StringGeneratorBundle\Generator\SecureStringGenerator');
    }

    function let(Factory $factory, Generator $low, Generator $medium)
    {
        $factory->getMediumStrengthGenerator()->willReturn($medium);
        $factory->getLowStrengthGenerator()->willReturn($low);
        $this->beConstructedWith($factory);

        $defaults = [
            'length' => 32,
            'chars' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'strength' => 'medium',
        ];

        $this->setOptions($defaults);
    }

    function it_chooses_a_specified_strength_gen(Generator $medium, Generator $low)
    {
        $this->getGenerator('medium')->shouldReturn($medium);
        $this->getGenerator('low')->shouldReturn($low);
    }

    function it_errors_on_invalid_strength_gen(Generator $medium, Generator $low)
    {
        $this->getGenerator('medium')->shouldReturn($medium);
        $this->getGenerator('low')->shouldReturn($low);

        $this->shouldThrow('\InvalidArgumentException')->duringGetGenerator('high');
        $this->shouldThrow('\InvalidArgumentException')->duringGetGenerator('invalid');
    }

    function it_generates_a_random_string(Generator $medium)
    {
        $medium->generateString(32, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')->shouldBeCalled();
        $this->generate();
    }

    function it_generates_a_random_string_of_set_length(Generator $medium, Generator $low)
    {
        $options = [
            'length' => 8,
            'chars' => '',
            'strength' => 'low',
        ];
        $this->setOptions($options);

        $medium->generateString(8, '')->shouldNotBeCalled();
        $low->generateString(8, '')->shouldBeCalled();

        $this->generate();
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
