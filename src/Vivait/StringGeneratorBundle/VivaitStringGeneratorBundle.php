<?php

namespace Vivait\StringGeneratorBundle;

use AppBundle\DependencyInjection\Compiler\GeneratorPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class VivaitStringGeneratorBundle extends Bundle
{

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new GeneratorPass());
    }
}
