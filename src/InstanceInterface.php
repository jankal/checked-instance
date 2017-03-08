<?php
namespace CheckedInstance;

/**
 * Interface InstanceInterface
 * @package CheckedInstance
 */
interface InstanceInterface
{
    public static function getRequired() : array;

    public function set($key, $value);

    public function check() : bool;
}
