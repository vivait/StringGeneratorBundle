# StringGeneratorBundle

[![Build Status](https://travis-ci.org/vivait/StringGeneratorBundle.svg?branch=master)](https://travis-ci.org/vivait/StringGeneratorBundle)

This bundle allows you to automatically generate a unique random string on an entity property, useful for
creating keys. Using Doctrine's `prePersist` callback, StringGenerator adds the generated string to a property
before the entity is persisted. It also checks whether the string is unique to that property (just in case) and if not, quietly
generates a new string.

## Install

Run: `composer require vivait/string-generator-bundle:^2.0` to install the bundle.

If you are using PHP 5.3 or 5.4 you can use the legacy version`vivait/string-generator-bundle:^1.1`

[*Check latest releases*](https://github.com/vivait/StringGeneratorBundle/releases)

Update your `AppKernel`:
```php
public function registerBundles()
{
    $bundles = array(
        ...
        new Vivait\StringGeneratorBundle\VivaitStringGeneratorBundle(),
}
```  
## Configure

The default configuration is shown below:

```yaml
vivait_string_generator:
  generators:
    string: vivait_generator.generator.string
    secure_bytes: vivait_generator.generator.secure_bytes
    secure_string: vivait_generator.generator.secure_string
    uuid_string: vivait_generator.generator.uuid_string
```

## Bundled generators

### `StringGenerator`
Generates a random string based on a pool or characters. Defaults:

```php
@Generate(generator="string", options={"length"=8, "chars"="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", "prefix"=""})
```

### `SecureStringGenerator`
Generates a secure random string using [ircmaxell's RandomLib](https://github.com/ircmaxell/RandomLib). The library provides three different strengths of
strings(currently `high` is unavailable), `low` and `medium`. Defaults:

```php
@Generate(generator="secure_string", options={"length"=32, "chars"="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", "strength"="medium"})
```

### `SecureBytesGenerator`
Generates a secure random byte string using the `Symfony\Component\Security\Core\Util\SecureRandom` class. Defaults:

```php
@Generate(generator="secure_bytes", options={"length"=8})
```

## Usage
Add the `@Generate(generator="generator_name")` annotation to an entity property
(where `generator_name` is the name of a generator defined in the configuration).

`generator` is a required property of the annotation.

```php
use Vivait\StringGeneratorBundle\Annotation\GeneratorAnnotation as Generate;

/**
 * Api
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Api
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="api_id", type="string", nullable=false)
     * @Generate(generator="string")
     */
    private $api_key;
```

### Options

Generators that implement `ConfigurableGeneratorInterface`, such as the bundled generators have options which can be configured.

To do this, set the options parameter on the annotation:

```php
@Generate(generator="string", options={"length"=32, "chars"="ABCDEFG"})
```

### Callbacks

It's possible to define callbacks on the `Generator` class that you are using.
For example, with the bundled StringGenerator, you may wish to set the a prefix on the generated string

This can be achieved by setting the `callbacks` option. For example:

```php
@Generate(generator="my_generator", callbacks={"setPrefix"="VIVA_"})
```

Here, `setChars()` is called in the `StringGenerator` class, passing `ABCDEFG` as a parameter.

It's even possible to set a callback value dynamically:

```php
/**
 * @var string
 *
 * @ORM\Column(name="short_id", type="string", length=255, nullable=false)
 * @Generate(generator="string", options={"length"=32}, callbacks={"setPrefix"="getPrefix"})
 */
private $short_id;

public function getPrefix()
{
    return $this->getType(); //"default"
}
```

In this case `StringGenerator::setPrefix("default")` will be called


### Unique

Setting `unique` is boolean and tell if the string must be unique or not, by default `true`

```php
@Generate(generator="secure_bytes", unique=false)
```

### Override

By default, `override` is set to true, so a string is always generated for a property.
However, by setting `override` to false, only null properties will have a string generated for them.

```php
@Generate(generator="string", override=false)
```


## Custom generators
You can use your own generators by implementing `GeneratorInterface` and defining the generator in the configuration,
using either its service or classname.

To create configurable generators, implement `ConfigurableGeneratorInterface`. This interface uses
[`Symfony\Component\OptionsResolver\OptionsResolver`](http://symfony.com/doc/current/components/options_resolver.html) to set the generator configuration.

Set default options:

```php
/**
* @param OptionsResolver $resolver
* @return mixed
*/
public function getDefaultOptions(OptionsResolver $resolver)
{
  $resolver->setDefaults([
    'chars' => $this->chars,
    'length' => $this->length,
    'prefix' => $this->prefix,
    ]);
  }
  ```

Do something with options:

  ```php
  /**
  * @param array $options
  * @return mixed|void
  */
  public function setOptions(array $options)
  {
    $this->chars = $options['chars'];
    $this->length = $options['length'];
    $this->prefix = $options['prefix'];
  }
  ```
