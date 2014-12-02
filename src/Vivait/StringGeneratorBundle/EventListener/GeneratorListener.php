<?php

namespace Vivait\StringGeneratorBundle\EventListener;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Vivait\StringGeneratorBundle\Annotation\GeneratorAnnotation;
use Vivait\StringGeneratorBundle\Model\GeneratorInterface;
use Vivait\StringGeneratorBundle\Registry\Registry;

class GeneratorListener
{
    private $reader;
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

        $obj = new \ReflectionObject($entity);

        //Loop through each of entity's properties
        foreach ($obj->getProperties() as $property) {
            //Loop through the property's annotations
            foreach ($this->reader->getPropertyAnnotations($property) as $annotation) {

                if ($annotation instanceof GeneratorAnnotation) {
                    if (method_exists($entity, $annotation->prefix_callback) && is_callable([$entity, $annotation->prefix_callback])) {
                        $callback = $annotation->prefix_callback;
                        $annotation->prefix = $entity->$callback();
                    }

                    $id = $this->generateString(
                        $property->name,
                        $annotation
                    );
                    $meta->getReflectionProperty($property->name)->setValue($entity, $id);
                }
            }
        }
    }

    /**
     * @param $property
     * @param $annotation
     * @return string
     */
    private function generateString($property, GeneratorAnnotation $annotation)
    {
        $generator = $this->registry->get($annotation->generator);
        $generator->setLength($annotation->length);

        $str = $this->generator->generate();

        if ($annotation->prefix) {
            $str = sprintf("%s%s%s", $annotation->prefix, $annotation->separator, $str);
        }

        if (!$annotation->unique) {
            return $str;
        }


        if ($this->repo->findOneBy([$property => $str])) {
            return $this->generateString($property, $annotation);
        } else {
            return $str;
        }

    }

}
