<?php
namespace Coda\Slim\Registrant;

use Interop\Container\ContainerInterface;

interface RegistratorInterface
{

    /**
     * Set DI container.
     *
     * @param \Interop\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface &$container);

    public function registerClass($classObject);
    public function register($className);
    public function run();
}