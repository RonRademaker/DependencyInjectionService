# DependencyInjectionService
Flexible service to populate (dynamic) objects using awareness interfaces and setter injection

# Introduction
The DependencyInjectionService solves the problem of populating objects, that are not services itself, with services from a Dependency Injection Container. A problem that often arises here is that it is unknown which services are supported. An example, with a possible solution, is described in http://toschas.com/aware-interfaces-symfony2/. The DependencyInjectionService takes a different approach where there is an object responsible for managing the dealings with awareness interfaces. This aims to have these advantages:

* Centralized configuration of the relation between an awareness interfaces and the service its aware of.
* Flexible configuration of which service should be used that is fully decoupled from the awareness interface.
* Easy to debug as there is one object responsible for populating these kind of object.
* I aim to support multiple dependency injection containers, so the solution won't be tied to a specific container.

# Possible usage example

``` php
class MyObject implements My\Package\Awareness\LoggerAwareInterface
{
  private $logger;

  public function setLogger(\Psr\Log\LoggerInterface $logger) 
  {
    $this->logger = $logger;
  }

  public function getLogger()
  {
    return $this->logger;
  }
}

class TheirObject implements Their\Package\Awareness\LoggerAwareInterface
{
  private $logger;

  public function setLogger(\Psr\Log\LoggerInterface $logger) 
  {
    $this->logger = $logger;
  }

  public function getLogger()
  {
    return $this->logger;
  }
}

class YetAnotherObject implements Someones\Package\Interfaces\LoggerAware
{
  private static $logger;
  
  public static function setLogger(\Psr\Log\LoggerInterface $logger) 
  {
    self::$logger = $logger;
  }

  public static function getLogger()
  {
    return self::$logger;
  }
}

$dependencyInjectionService = new ConcreteDependencyInjectionService();
$dependencyInjectionService->registerService('My\Package\Awareness\LoggerAwareInterface', 'my.logger', 'setLogger');
$dependencyInjectionService->registerService('Their\Package\Awareness\LoggerAwareInterface', 'my.logger', 'setLogger');
$dependencyInjectionService->registerService('Someones\Package\Interfaces\LoggerAware', 'my.logger', 'setLogger', true); // boolean indicates static

$myLoggerAwareObject = new MyObject(); 
$dependenyInjectionService->populateServices($container, $myLoggerAwareObject);

$theirLoggerAwareObject = new TheirObject(); 
$dependenyInjectionService->populateServices($container, $theirLoggerAwareObject);

$dependenyInjectionService->populateServices($container, 'YetAnotherObject'); // instantiation is also allowed

assert($myLoggerAwareObject->getLogger() === $theirLoggerAwareObject->getLogger());
assert($myLoggerAwareObject->getLogger() === YetAnotherObject::getLogger());
```

The configuration created by the registerService calls should usually be done in the configuration of the dependency injection container. An example for symfony using the above example:

``` xml
<?xml version="1.0" encoding="UTF-8"?>
<container xmlns='http://symfony.com/schema/dic/services'
  xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'
  xsi:schemaLocation='http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd'>

  <services>
    <service id='my.logger' class='Exotic/Cool/Logger'/>
    <service id='dependencyinjectionservice' class='RonRademaker\DependencyInjectionService\DependencyInjectionService'>
      <call method='registerService'>
        <argument>My\Package\Awareness\LoggerAwareInterface</argument>
        <argument>my.logger</argument>
        <argument>setLogger</argument>
      </call>
      <call method='registerService'>
        <argument>Their\Package\Awareness\LoggerAwareInterface</argument>
        <argument>my.logger</argument>
        <argument>setLogger</argument>
      </call>
      <call method='registerService'>
        <argument>Someones\Package\Interfaces\LoggerAware</argument>
        <argument>my.logger</argument>
        <argument>setLogger</argument>
        <argument>true</argument>
      </call>
    </service>
  </services>
</container>
```

In think my.logger should **not** be allowed to be a service, because it would result in any creation of the DependencyInjectionContainer triggering the creation of any services it can possibly populate something with. That would have an unnecessary negative impact on performance. Anything using the DependencyInjectionService should be Container Aware.

Resulting in code:
``` php
$dependenyInjectionService = $container->get('dependencyinjectionservice');

$myLoggerAwareObject = new MyObject(); 
$dependenyInjectionService->populateServices($container, $myLoggerAwareObject);

$theirLoggerAwareObject = new TheirObject(); 
$dependenyInjectionService->populateServices($container, $theirLoggerAwareObject);

$dependenyInjectionService->populateServices($container, 'YetAnotherObject'); // instantiation is also allowed

assert($myLoggerAwareObject->getLogger() === $theirLoggerAwareObject->getLogger());
assert($myLoggerAwareObject->getLogger() === YetAnotherObject::getLogger());
```
