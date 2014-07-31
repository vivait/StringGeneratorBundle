# StringGeneratorBundle

This bundle allows you to automatically generate a unique random string on an entity property, useful for
creating keys or passwords. Using Doctrine's `prePersist` callback, StringGenerator adds the generated string to a property
before the entity is persisted. It also checks whether the string is unique to that property (just in case) and quietly
generates a new string. This results as a minimum of one extra query whenever you flush an entity for the first time.

## Install

Add `"vivait/string-generator-bundle": "dev-master"` to your composer.json and run `composer update`

Update your `AppKernel`:

    public function registerBundles()
    {
        $bundles = array(
            ...
            new Vivait\StringGeneratorBundle\VivaitStringGeneratorBundle(),
    }

## Basic usage

Add the `@Vivait\StringGenerator()` annotation to an entity property

    use Vivait\StringGeneratorBundle\Annotation as Vivait;
    
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
         * @Vivait\StringGenerator()
         */
        private $api_key;

## Options

### Length

To change the length of the generated string, add `length` to the annotation. The default is 8. Length specifies the length
of the resulting generated string, and does not include the prefix or separator

    @Vivait\StringGenerator(length=4)
    
### Prefix the key

To prefix the string, add the `prefix` option to the annotation. The default seperator between the prefix and generated
string is a `-`, but this can be changed using `separator`:

    @Vivait\StringGenerator(prefix="user", separator="_")

A prefix can be obtained via a callback to a method in the entity using `prefix_callback`, which overrides `prefix`.

    /**
     * @var string
     *
     * @ORM\Column(name="friendly_id", type="string", nullable=false)
     * @Vivait\StringGenerator(prefix_callback="createPrefix", length=8)
     */
    private $friendly_id;
    
    public function createPrefix()
    {
        return $this->category->getCode();
    }
    
### Custom characters

Setting `alphabet` limits the characters the generator can choose from. Defaults to alphanumeric.

    @Vivait\StringGenerator(alphabet="abcdefghkmnpqrstuwxyz")