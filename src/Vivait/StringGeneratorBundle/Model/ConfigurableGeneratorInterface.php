<?php

namespace Vivait\StringGeneratorBundle\Model;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface ConfigurableGeneratorInterface extends GeneratorInterface
{
    /**
     * @param array $options
     * @return mixed
     */
    public function setOptions(array $options = []);

    /**
     * @param OptionsResolver $resolver
     * @return mixed
     */
    public function getDefaultOptions(OptionsResolver $resolver);
}
