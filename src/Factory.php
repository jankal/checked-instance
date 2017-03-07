<?php
namespace CheckedInstance;

/**
 * Class Factory
 * @package CheckedInstance
 */
class Factory
{
    /**
     * @var string
     */
    protected $for;

    /**
     * @var array
     */
    private $vars = [];

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
            throw new FactoryFailureException($class . ' is not implementing the InstanceInterface!');
        }
        foreach ($this->vars as $name => $var) {
            $instatnce->set($name, $var);
        }
        $instatnce->check();
        return $instatnce;
    }

    /**
     * @param $key
     * @param $value
     * @return Factory
     */
    public function with($key, $value)
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
     * @param string $class
     * @return Factory
     */
    public static function for(string $class)
    {
        $instance = new self();
        $instance->for = $class;
        return $instance;
    }
}
