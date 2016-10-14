# Slim framework dynamic route register container
## Using especially on group route in slim framework

## Usage

``` php

// example api user class

namespace My\App\Route\Api;

use Slim\Http\Request as Request;
use Slim\Http\Response as Response;
use Interop\Container\ContainerInterface;

class UserController{

    protected $container;

    public function __construct(ContainerInterface &$container)
    {
        $this->container = $container;
    }

    public function hello(Request $request, Response $response, $args)
    {
        $name = empty($args) ? " World" : $args['name'];
        return $response
               ->withHeader('Content-Type', 'application/json')
               ->write('Hello ' . $name );
    }

}

// register container

$container = $app->getContainer();
$container['Registrator'] = function($c) {
    return new \Coda\Slim\Registrant\Registrator($c);
};


// usage on slim route

$app->group('/api', function (){
    $container = $this->getContainer();

    $this->group('/user', function() use($container){
        $container['Registrator']->setAlias('UserControllers')->register('\My\App\Route\Api\UserController', $container)->run();

        $this->get('[/{name}]', 'UserControllers:hello')->setName('hello-user');
    });
});

```


##### register

Register instance of class into container based on class name
```
// register
$container['Registrator']->register('\My\ApiClass', ...$restArguments)->run();

// on route
$this->get('[/{id}]', '\My\ApiClass:method');
```

##### registerClass
Register instance of class into container based on class object

```
// register
$apiClass = new \My\ApiClass($args1, $args2);
$container['Registrator']->registerClass($apiClass)->run();

// on route
$this->get('[/{id}]', '\My\ApiClass:method');
```

##### setAlias
Set an alias on container for your class instance. so you can called only alias in route

```
// register
$container['Registrator']->setAlias('MyAlias')->register('\My\TrueClass', $container)->run();

// on route
$this->get('[/{id}]', 'MyAlias:method');
```