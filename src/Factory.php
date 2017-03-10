<?php
namespace CheckedInstance;

use Psr\Container\ContainerInterface;

/**
 * Class Factory
 * @package CheckedInstance
 */
class Factory
{
    /**
     * @var ContainerInterface
     */
    protected static $container;
    /**
     * @var string
     */
    protected $for;
    /**
     * @var string
     */
    protected $prefix = '';
    /**
     * @var array
     */
    private $vars = [];

    /**
     * @param string $class
     * @return Factory
     */
    public static function for (string $class) : Factory
    {
        $instance = new self();
        $instance->for = $class;
        return $instance;
    }

    /**
     * @param ContainerInterface $c
     */
    public static function container(ContainerInterface $c)
    {
        self::$container = $c;
    }

    /**
     * @param string $prefix
     * @return Factory
     */
    public static function prefix(string $prefix) : Factory
    {
        $instance = new self();
        $instance->prefix = $prefix;
        return $instance;
    }

    /**
     * @param string|null $class
     * @return InstanceInterface
     * @throws FactoryFailureException
     */
    public function make(string $class = null) : InstanceInterface
    {
        if (empty($class) && !empty($this->for)) {
            return $this->make($this->for);
        }
        if (empty($class)) {
            throw new FactoryFailureException('Please configure or set class to make instance from!');
        }
        $instatnce = new $class();
        if (!($instatnce instanceof InstanceInterface)) {
            throw new FactoryFailureException($class.' is not implementing the InstanceInterface!');
        }
        if (isset(self::$container)) {
            $this->setFromContainer($class);
        } else {
            $this->guessInstantiation($class);
        }
        foreach ($this->vars as $name => $var) {
            $instatnce->set($name, $var);
        }
        $instatnce->check();
        return $instatnce;
    }

    /**
     * @param string $for
     */
    private function setFromContainer(string $for)
    {
        $req = $for::getRequired();
        if (array_values($req) == $req) {
            foreach ($req as $field) {
                if (!isset($this->vars[$field]) && self::$container->has($this->prefix.$field)) {
                    $this->vars[$field] = self::$container->get($this->prefix.$field);
                }
            }
        } else {
            foreach ($req as $implementation => $field) {
                if (!isset($this->vars[$field])) {
                    if (class_exists($implementation)) {
                        if (self::$container->has($implementation)) {
                            $this->vars[$field] = self::$container->get($implementation);
                        } elseif ($this->classInstantiateable($implementation)) {
                            $this->vars[$field] = new $implementation();
                        }
                    } else {
                        $this->vars[$field] = self::$container->get($this->prefix.$field);
                    }
                }
            }
        }
    }

    /**
     * @param $implementation
     * @return bool
     */
    private function classInstantiateable($implementation) : bool
    {
        $class = new \ReflectionClass($implementation);
        if (!$class->hasMethod('__construct')) {
            return true;
        }
        $constructor = $class->getConstructor();
        return $constructor->getNumberOfRequiredParameters() == 0;
    }

    /**
     * @param string $class
     */
    private function guessInstantiation(string $class)
    {
        $req = $class::getRequired();
        if (array_values($req) != $req) {
            foreach ($req as $implementation => $field) {
                if (!is_numeric($implementation)) {
                    if (class_exists($implementation) && $this->classInstantiateable($implementation)) {
                        $this->vars[$field] = new $implementation();
                    }
                }
            }
        }
    }

    /**
     * @param $key
     * @param $value
     * @return Factory
     */
    public function with($key, $value) : Factory
    {
        $this->vars[$key] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getVars() : array
    {
        return $this->vars;
    }

    /**
     * @return string
     */
    public function getFor() : string
    {
        return $this->for;
    }

    /**
     * @param string $for
     * @return Factory
     */
    public function as (string $for) : Factory
    {
        $this->for = $for;
        return $this;
    }
}
