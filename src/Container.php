<?php

namespace NoteScript;

/**
 * A dependency injection container.
 */
class Container
{
    const FACTORY_NOT_FOUND = 'Factory not found';

    /**
     * @var array An array of configuration values.
     */
    private $config = [];

    /**
     * @var array An array of registered factories used to create object instances.
     */
    private $factories = [];

    /**
     * @var array An array of object instances.
     */
    private $instances = [];

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Retrieve the config array that was passed in at instantiation.
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Register a factory with a name that can be used to execute it.
     * @param string $name The name to use to call the factory.
     * @param callable $factory A callable factory to instantiate an object.
     * @return void
     */
    public function registerFactory($name, callable $factory)
    {
        $this->factories[$name] = $factory;
    }

    /**
     * Retrieve an object using the given factory name.
     * @param string $name The name of the factory to use to retrieve the object.
     * @return mixed
     * @throws ContainerException
     */
    public function get($name)
    {
        if (array_key_exists($name, $this->instances)) {
            return $this->instances[$name];
        }

        if (!array_key_exists($name, $this->factories)) {
            throw new ContainerException(self::FACTORY_NOT_FOUND);
        }

        return $this->instances[$name] = call_user_func($this->factories[$name], $this);
    }
}
