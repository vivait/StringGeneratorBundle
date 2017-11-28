<?php

namespace Vivait\StringGeneratorBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class VivaitStringGeneratorExtension extends ConfigurableExtension
{

    /**
     * {@inheritdoc}
     */
    public function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        if ($mergedConfig['generators'] && $container->hasDefinition('vivait_generator.registry')) {
            @trigger_error(
                'Defining Generators in config is deprecated since version 2.0.1 and will be removed in version 3.0. ' .
                'Use services tagged with "vivait_generator.generator" and an "alias" instead.',
                E_USER_DEPRECATED
            );

            $registry = $container->findDefinition('vivait_generator.registry');

            $legacyGenerators = [];

            foreach ($mergedConfig['generators'] as $alias => $generatorService) {
                $legacyGenerators[$alias] = $container->get($generatorService);
            }

            $registry->addArgument($legacyGenerators);
        }
    }
}
