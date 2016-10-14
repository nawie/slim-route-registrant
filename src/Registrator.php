<?php
namespace Coda\Slim\Registrant;

use Interop\Container\ContainerInterface;

class Registrator  implements RegistratorInterface
{

    /**
     * DI container.
     *
     * @var \Interop\Container\ContainerInterface
     */
    protected $container;
    protected $registeredClassAlias;
    protected $registeredClass;

    /**
     * Set DI container.
     *
     * @param \Interop\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface &$container)
    {
        $this->container = $container;
    }

    /**
     * Bridge container get.
     *
     * @param string $name
     */
    final public function __get($name)
    {
        return $this->container->get($name);
    }

    /**
     * Bridge container has.
     *
     * @param string $name
     */
    final public function __isset($name)
    {
        return $this->container->has($name);
    }

    final public function setAlias($alias)
    {
        $this->registeredClassAlias = $alias;
        return $this;
    }
    /**
     * Set from instance class
     * @param  object $classObject instance of class
     * @return self
     */
    final public function registerClass($classObject)
    {
        if(!is_object($classObject)){
            throw new \InvalidArgumentException( "Argument is not a valid instance of class");
        }
        $this->registeredClass = $classObject;
        return $this;
    }

    /**
     * Create instance of class
     * @param  object $className class name
     * @return self
     */
    final public function register($className)
    {
        $classRef = new \ReflectionClass($className);
        $classArgs = array_slice(func_get_args(), 1);
        $this->registeredClass = $classRef->newInstanceArgs($classArgs);
        return $this;
    }

    /**
     * Register class object
     */
    final public function run()
    {
        $registeredName = ($this->registeredClassAlias !== null) ? $this->registeredClassAlias : get_class($this->registeredClass);
        $registeredClass = $this->registeredClass;

        $this->container[$registeredName] = function($c) use(&$registeredClass){
            return $registeredClass;
        };
        $this->reset();
    }

    /**
     * Reset instance of class object and alias
     */
    final protected function reset()
    {
        $this->registeredClass = null;
        $this->registeredClassAlias = null;
    }

}