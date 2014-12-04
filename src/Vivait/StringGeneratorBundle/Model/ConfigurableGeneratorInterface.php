<?php

namespace Vivait\StringGeneratorBundle\Model;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface ConfigurableGeneratorInterface extends GeneratorInterface
{
    public function setOptions(array $options = []);

    public function getDefaultOptions(OptionsResolver $resolver);
}
