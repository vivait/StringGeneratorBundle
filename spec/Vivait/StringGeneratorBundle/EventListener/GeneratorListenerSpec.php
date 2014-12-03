<?php

namespace spec\Vivait\StringGeneratorBundle\EventListener;

use Doctrine\Common\Annotations\Reader;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
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

    function let(Reader $reader, Registry $registry, Entity $mockEntity, EntityRepository $entityRepository, ClassMetadata $meta, EntityManagerInterface $entityManager, LifecycleEventArgs $args)
    {
        $this->beConstructedWith($reader, $registry);

        //Set up for EM etc.
        $args->getEntity()->willReturn($mockEntity);
        $args->getEntityManager()->willReturn($entityManager);
        $entityManager->getClassMetadata(Argument::any())->willReturn($meta);
        $entityManager->getRepository(Argument::any())->willReturn($entityRepository);
        $meta->getName()->willReturn('Entity');

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

    function it_sets_only_null_properties_if_specified(Reader $reader, LifecycleEventArgs $args, StringGenerator $generator, Entity $mockEntity)
    {
        $mockEntity->setName(null);

        $annotation = new GeneratorAnnotation([]);
        $annotation->nullOnly = true;

        $reader->getPropertyAnnotations(Argument::any())->willReturn([$annotation]);

        $generator->generate()->shouldNotBeCalled();
        $this->prePersist($args);
    }

    function it_generates_a_string_on_a_property(Registry $registry, Reader $reader, LifecycleEventArgs $args, StringGenerator $generator)
    {
        $annotation = new GeneratorAnnotation([]);

        $reader->getPropertyAnnotations(Argument::any())->willReturn([$annotation]);

        $registry->get(Argument::any())->willReturn($generator);

        $generator->generate()->shouldBeCalled();
        $generator->setLength(Argument::any())->shouldBeCalled();


        $this->prePersist($args);
    }
}

class Entity
{
    private $name;

    public function getPrefix()
    {

    }

    public function setName($name)
    {
        $this->name = $name;
    }
}

