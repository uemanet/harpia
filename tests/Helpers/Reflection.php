<?php
declare(strict_types=1);

namespace Tests\Helpers;

trait Reflection
{
    /**
     * Call protected and private methods
     *
     * @param $object
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    private function invokeMethod(&$object, string $method, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));

        $method = $reflection->getMethod($method);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}