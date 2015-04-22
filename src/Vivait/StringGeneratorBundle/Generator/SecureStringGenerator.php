<?php

namespace Vivait\StringGeneratorBundle\Generator;

use RandomLib\Factory;
use RandomLib\Generator;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vivait\StringGeneratorBundle\Model\ConfigurableGeneratorInterface;

class SecureStringGenerator implements ConfigurableGeneratorInterface
{

    /**
     * @var int
     */
    private $length = 32;

    /**
     * @var string
     */
    private $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * @var string
     */
    private $strength = 'medium';

    /**
     * @var Factory
     */
    private $factory;

    /**
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Creates a random string based on a length and alphabet
     *
     * @return string
     */
    public function generate()
    {
        return $this->getGenerator($this->strength)->generateString($this->length, $this->chars);
    }

    public function getGenerator($strength)
    {
        switch ($strength) {
            case 'low':
                return $this->factory->getLowStrengthGenerator();
            case 'medium':
                return $this->factory->getMediumStrengthGenerator();
            case 'high':
                throw new \InvalidArgumentException('"high" strength is currently unavailable');
            default:
                throw new \InvalidArgumentException('Could not find a generator for the specified strength');
        }
    }

    public function setOptions(array $options)
    {
        $this->strength = $options['strength'];
        $this->length = $options['length'];
        $this->chars = $options['chars'];
    }

    public function getDefaultOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults(
            [
                'length' => $this->length,
                'chars' => $this->chars,
                'strength' => $this->strength,
            ]
        );
    }

    /**
     * @param integer $length
     * @return ConfigurableGeneratorInterface
     * @deprecated this will be deprecated in version 2.0 in favour of using callbacks on the generator. This is due to
     * some generators not actually needing a length - only random string type generators require it.
     */
    public function setLength($length)
    {
        $this->length = $length;
        return $this;
    }
}
