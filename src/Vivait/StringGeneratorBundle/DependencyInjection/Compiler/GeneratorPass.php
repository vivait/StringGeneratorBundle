<?php

namespace Vivait\StringGeneratorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class GeneratorPass implements CompilerPassInterface
{

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ( ! $container->has('vivait_generator.registry')) {
            return;
        }

        $definition    = $container->findDefinition('vivait_generator.registry');
        $allGenerators = $container->findTaggedServiceIds('vivait_generator.generator');

        // Loop through all service IDs
        foreach ($allGenerators as $id => $tags) {
            // Loop through all of the individual tags
            foreach ($tags as $attributes) {
                $definition->addMethodCall(
                    'addGenerator',
                    [
                        new Reference($id),
                        $attributes['alias']
                    ]
                );
            }
        }
    }
}
