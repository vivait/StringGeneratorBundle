<?php

namespace Vivait\StringGeneratorBundle\EventListener;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vivait\StringGeneratorBundle\Annotation\GeneratorAnnotation;
use Vivait\StringGeneratorBundle\Model\ConfigurableGeneratorInterface;
use Vivait\StringGeneratorBundle\Model\GeneratorInterface;
use Vivait\StringGeneratorBundle\Registry\Registry;

class GeneratorListener
{
    /**
     * @var Reader
     */
    private $reader;
    /**
     * @var EntityRepository
     */
    private $repo;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Registry has been made nullable as injecting it causes circular references. Instead, the container is injected
     * via a setter, and the registry is fetched from there instead.
     *
     * @param Reader $reader
     * @param null|Registry $registry
     */
    public function __construct(Reader $reader, Registry $registry = null)
    {
        $this->reader = $reader;
        $this->registry = $registry;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        $meta = $em->getClassMetadata(get_class($entity));
        $this->repo = $em->getRepository($meta->getName());

        $currentObject = new \ReflectionObject($entity);
        $properties = [];
        do {
            foreach ($currentObject->getProperties() as $property) {
                $properties[] = $property;
            }
        } while (($currentObject = $currentObject->getParentClass()) && (false !== $currentObject));

        foreach ($object->getProperties() as $property) {
            foreach ($this->reader->getPropertyAnnotations($property) as $annotation) {
                if ($annotation instanceof GeneratorAnnotation) {

                    $property->setAccessible(true);
                    if (!$annotation->override && $property->getValue($entity)) {
                        break;
                    }

                    $string = $this->generateString($property->name, $annotation, $entity);
                    $property->setValue($entity, $string);
                }
            }
        }
    }

    /**
     * @param string              $property
     * @param GeneratorAnnotation $annotation
     * @param object              $entity
     *
     * @return string
     */
    private function generateString($property, GeneratorAnnotation $annotation, $entity)
    {
        /** @var GeneratorInterface|ConfigurableGeneratorInterface $generator */
        $generator = $this->getRegistry()->get($annotation->generator);

        $generator->setLength($annotation->length);

        if(!empty($annotation->callbacks)){
            $this->performCallbacks($generator, $annotation, $entity);
        }

        if($generator instanceof ConfigurableGeneratorInterface){
            $generator = $this->configureGenerator($generator, $annotation->options);
        }

        $str = $generator->generate();

        if (!$annotation->unique) {
            return $str;
        }

        if ($this->repo->findOneBy([$property => $str])) {
            return $this->generateString($property, $annotation, $entity);
        } else {
            return $str;
        }
    }

    /**
     * @param ConfigurableGeneratorInterface $generator
     * @param $options
     * @return \Vivait\StringGeneratorBundle\Model\ConfigurableGeneratorInterface
     */
    public function configureGenerator(ConfigurableGeneratorInterface $generator, $options)
    {
        $resolver = new OptionsResolver();
        $generator->getDefaultOptions($resolver);

        $options = $resolver->resolve($options);
        $generator->setOptions($options);

        return $generator;
    }

    /**
     * @param GeneratorInterface  $generator
     * @param GeneratorAnnotation $annotation
     * @param object              $object
     */
    public function performCallbacks(GeneratorInterface $generator, GeneratorAnnotation $annotation, $object)
    {
        foreach($annotation->callbacks as $callback => $value){
            if($this->isMethod($generator, $callback)) {
                if($this->isMethod($object, $value)) {
                    $value = $object->$value();
                }

                $generator->$callback($value);
            } else {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Callback "%s" does not exist in class "%s"',
                        $callback,
                        get_class($generator)
                    )
                );
            }
        }
    }

    /**
     * @param $class
     * @param $callback
     * @return bool
     */
    private function isMethod($class, $callback)
    {
        return method_exists($class, $callback) && is_callable([$class, $callback]);
    }

    /**
     * @return Registry
     */
    private function getRegistry()
    {
        if($this->registry){
            return $this->registry;
        }
        return $this->container->get('vivait_generator.registry');
    }
}
