<?php
namespace CheckedInstance;

/**
 * Class Instance
 * @package CheckedInstance
 */
class Instance implements InstanceInterface
{
    /**
     * @var array
     */
    protected $required = [];

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @return array
     */
    public static function getRequired() : array
    {
        $i = new static();
        return $i->required;
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->config[$key] = $value;
    }

    /**
     * @return bool
     * @throws InstanceCorruptException
     */
    public function check() : bool
    {
        if ($this->required == array_values($this->required)) {
            foreach ($this->required as $key) {
                if (!isset($this->config[$key])) {
                    throw new InstanceCorruptException(get_class($this).':'.$key.' not set!');
                }
            }
        } else {
            foreach ($this->required as $instance => $key) {
                if (!isset($this->config[$key])) {
                    throw new InstanceCorruptException(get_class($this).':'.$key.' not set!');
                }
                if (!is_numeric($instance)) {
                    if (!($this->config[$key] instanceof $instance)) {
                        throw new InstanceCorruptException(get_class($this).':'.$key.' not instance of '.$instance.'!');
                    }
                }
            }
        }
        return true;
    }

    /**
     * @param $key
     * @return mixed
     */
    protected function get($key)
    {
        return $this->config[$key] ?? null;
    }
}
