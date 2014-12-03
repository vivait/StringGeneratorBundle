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
    public $mockEntity;

    function it_is_initializable()
    {
        $this->shouldHaveType('Vivait\StringGeneratorBundle\EventListener\GeneratorListener');
    }

    function let(Reader $reader, Registry $registry, EntityRepository $entityRepository, ClassMetadata $meta, EntityManagerInterface $entityManager, LifecycleEventArgs $args)
    {
        $this->beConstructedWith($reader, $registry);

        //Set up for EM etc.
        $this->mockEntity = new Entity();
        $args->getEntity()->willReturn($this->mockEntity);
        $args->getEntityManager()->willReturn($entityManager);
        $entityManager->getClassMetadata(Argument::any())->willReturn($meta);
        $entityManager->getRepository(Argument::any())->willReturn($entityRepository);
        $meta->getName()->willReturn('Entity');

    }

    function it_performs_callbacks_on_the_generator(StringGenerator $generator)
    {
        $annotation = new GeneratorAnnotation([]);
        $annotation->callbacks = ['setChars' => 'abcdef'];
        $this->shouldNotThrow('\InvalidArgumentException')->duringPerformCallbacks($generator, $annotation, $this->mockEntity);

        $annotation->callbacks = ['noMethod' => 'something'];
        $this->shouldThrow('\InvalidArgumentException')->duringPerformCallbacks($generator, $annotation, $this->mockEntity);
    }

    function it_can_get_callback_values_from_annotated_object(StringGenerator $generator)
    {
        $annotation = new GeneratorAnnotation([]);
        $annotation->callbacks = ['setPrefix' => 'createPrefix'];

        $this->mockEntity->createPrefix();
        $generator->setPrefix('VIVA_')->shouldBeCalled();
        $this->performCallbacks($generator, $annotation, $this->mockEntity);
    }

    function it_sets_null_properties_if_override_set_to_true(Registry $registry, Reader $reader, LifecycleEventArgs $args, StringGenerator $generator)
    {
        $annotation = new GeneratorAnnotation([]);
        $annotation->override = false;
        $annotation->unique = false;

        $reader->getPropertyAnnotations(Argument::any())->willReturn([$annotation]);

        $registry->get(Argument::any())->willReturn($generator)->shouldBeCalled();
        $generator->generate()->shouldBeCalled();
        $generator->setLength(Argument::any())->shouldBeCalled();

        $this->prePersist($args);
    }
    function it_wont_set_non_null_properties_if_override_set_to_true(Reader $reader, LifecycleEventArgs $args, StringGenerator $generator)
    {
        $this->mockEntity->setName('Robin');
        $annotation = new GeneratorAnnotation([]);
        $annotation->override = false;

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

    public function createPrefix()
    {
        return 'VIVA_';
    }

    public function setName($name)
    {
        $this->name = $name;
    }

}

