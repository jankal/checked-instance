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
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->config[$key] = $value;
    }

    /**
     * @param $key
     * @return mixed
     */
    protected function get($key)
    {
        return $this->config[$key];
    }

    /**
     * @return bool
     * @throws InstanceCorruptException
     */
    public function check() : bool
    {
        foreach ($this->required as $key) {
            if (!isset($this->config[$key])) {
                throw new InstanceCorruptException(get_class($this) . ':' . $key . ' not set!');
            }
        }
        return true;
    }
}
