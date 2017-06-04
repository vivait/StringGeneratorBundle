<?php

namespace spec\Vivait\StringGeneratorBundle\Generator;

use PhpSpec\ObjectBehavior;

class UuidGeneratorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Vivait\StringGeneratorBundle\Generator\UuidGenerator');
    }

    function let()
    {

    }

    function it_should_throw_an_exception_when_no_namespace_on_version_3()
    {
        $this->setVersion(3);
        $this->setNamespace(null);
        $this->shouldThrow('\RuntimeException')->duringGenerate();
    }

    function it_should_throw_an_exception_when_no_namespace_on_version_5()
    {
        $this->setVersion(5);
        $this->setNamespace(null);
        $this->shouldThrow('\RuntimeException')->duringGenerate();
    }

    function it_generates_a_uuid_based_on_version_1()
    {
        $this->setVersion(1);
        $this->generate()->shouldBeString();
        $this->generate()->shouldHaveStrlen(36);
    }

    function it_generates_a_uuid_based_on_version_3()
    {
        $this->setVersion(3);
        $this->setNamespace('testing');
        $this->generate()->shouldBeString();
        $this->generate()->shouldHaveStrlen(36);
    }

    function it_generates_a_uuid_based_on_version_4()
    {
        $this->setVersion(4);
        $this->generate()->shouldBeString();
        $this->generate()->shouldHaveStrlen(36);
    }

    function it_generates_a_uuid_based_on_version_5()
    {
        $this->setVersion(5);
        $this->setNamespace('testing');
        $this->generate()->shouldBeString();
        $this->generate()->shouldHaveStrlen(36);
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