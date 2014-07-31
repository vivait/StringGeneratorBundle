<?php

namespace Vivait\StringGeneratorBundle\EventListener;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Vivait\StringGeneratorBundle\Annotation as Vivait;
use Vivait\StringGeneratorBundle\Generator\StringGenerator;
use Vivait\StringGeneratorBundle\Model\StringGeneratorInterface;

class IdGeneratorListener
{
    private $reader;
    private $repo;

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
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

                if ($annotation instanceof Vivait\StringGenerator) {
                    if (method_exists($entity, $annotation->prefix_callback) && is_callable([$entity, $annotation->prefix_callback])) {
                        $callback = $annotation->prefix_callback;
                        $annotation->prefix = $entity->$callback();
                    }

                    $id = $this->generateId(
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
    private function generateId($property, Vivait\StringGenerator $annotation)
    {
        $generator = new StringGenerator();
        $generator
            ->setAlphabet($annotation->alphabet)
            ->setLength($annotation->length);

        $id = $$this->generator->generate();

        if ($annotation->prefix) {
            $id = sprintf("%s%s%s", $annotation->prefix, $annotation->separator, $id);
        }

        if ($this->repo->findOneBy([$property => $id])) {
            $this->generateId($property, $annotation);
        } else {
            return $id;
        }
    }


} 