<?php
namespace Tests;

/**
 * Class TestContainer
 * @package Tests
 */
class TestContainer implements \Psr\Container\ContainerInterface
{
    /**
     * @var array
     */
    private $source = [];

    public function get($id)
    {
        return $this->source[$id] ?? null;
    }

    /**
     * @param $id
     * @param $o
     */
    public function set($id, $o)
    {
        $this->source[$id] = $o;
    }

    public function has($id)
    {
        return isset($this->source[$id]);
    }
}
