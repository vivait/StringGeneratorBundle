<?php

namespace spec\Vivait\StringGeneratorBundle\EventListener;

use Doctrine\Common\Annotations\Reader;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Vivait\StringGeneratorBundle\Annotation\GeneratorAnnotation;
use Vivait\StringGeneratorBundle\Generator\StringGenerator;
use Vivait\StringGeneratorBundle\Registry\Registry;

class GeneratorListenerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Vivait\StringGeneratorBundle\EventListener\GeneratorListener');
    }

    function let(Reader $reader, Registry $registry)
    {
        $this->beConstructedWith($reader, $registry);
    }

    function it_performs_callbacks_on_the_generator(StringGenerator $generator, Entity $mockEntity)
    {
        $annotation = new GeneratorAnnotation([]);
        $annotation->callbacks = ['setChars' => 'abcdef'];
        $this->shouldNotThrow('\InvalidArgumentException')->duringPerformCallbacks($generator, $annotation, $mockEntity);

        $annotation->callbacks = ['noMethod' => 'something'];
        $this->shouldThrow('\InvalidArgumentException')->duringPerformCallbacks($generator, $annotation, $mockEntity);
    }

    function it_can_get_callback_values_from_annotated_object(StringGenerator $generator, Entity $mockEntity)
    {
        $annotation = new GeneratorAnnotation([]);
        $annotation->callbacks = ['setPrefix' => 'getPrefix'];

        $mockEntity->getPrefix()->willReturn('VIVA_');
        $generator->setPrefix('VIVA_')->shouldBeCalled();
        $this->performCallbacks($generator, $annotation, $mockEntity);
    }
}

class Entity
{
    public function getPrefix()
    {

    }
}

