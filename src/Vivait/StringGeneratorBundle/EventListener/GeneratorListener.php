<?php

namespace Vivait\StringGeneratorBundle\EventListener;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Vivait\StringGeneratorBundle\Annotation\GeneratorAnnotation;
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
     * @var GeneratorInterface
     */
    private $generator;
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param Reader $reader
     * @param Registry $registry
     */
    public function __construct(Reader $reader, Registry $registry)
    {
        $this->reader = $reader;
        $this->registry = $registry;
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

        $object = new \ReflectionObject($entity);

        foreach ($object->getProperties() as $property) {
            foreach ($this->reader->getPropertyAnnotations($property) as $annotation) {
                if ($annotation instanceof GeneratorAnnotation) {

                    $property->setAccessible(true);
                    if ($annotation->nullOnly && !$property->getValue($object)) {
                        break;
                    }

                    $string = $this->generateString($property->name, $annotation, $object);
                    $property->setValue($entity, $string);
                }
            }
        }
    }

    /**
     * @param $property
     * @param GeneratorAnnotation $annotation
     * @param $object
     * @return string
     */
    private function generateString($property, GeneratorAnnotation $annotation, $object)
    {
        /** @var GeneratorInterface $generator */
        $generator = $this->registry->get($annotation->generator);
        $generator->setLength($annotation->length);

        if(!empty($annotation->callbacks)){
            $this->performCallbacks($generator, $annotation, $object);
        }

        $str = $generator->generate();

        if (!$annotation->unique) {
            return $str;
        }

        if ($this->repo->findOneBy([$property => $str])) {
            return $this->generateString($property, $annotation, $object);
        } else {
            return $str;
        }
    }

    /**
     * @param GeneratorInterface $generator
     * @param GeneratorAnnotation $annotation
     * @param $object
     */
    public function performCallbacks(GeneratorInterface $generator, GeneratorAnnotation $annotation, $object)
    {
        foreach($annotation->callbacks as $callback => $value){
            if($this->isMethod($generator, $callback)){

                if($this->isMethod($object, $value)){
                    $value = $object->$value();
                }
                $generator->$callback($value);
            }
            else{
                throw new \InvalidArgumentException(sprintf(
                    'Callback "%s" does not exist in class "%s"',
                    $callback,
                    get_class($generator))
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
}
