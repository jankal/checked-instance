<?php
namespace CheckedInstance;

/**
 * Interface InstanceInterface
 * @package CheckedInstance
 */
interface InstanceInterface
{
    public function set($key, $value);
    public function check() : bool;
}
