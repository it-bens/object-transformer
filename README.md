# The Object Transformer

![Maintenance Status](https://img.shields.io/badge/Maintained%3F-yes-green.svg)
[![Build Status](https://app.travis-ci.com/it-bens/object-transformer.svg?branch=master)](https://app.travis-ci.com/it-bens/object-transformer)
[![Coverage Status](https://coveralls.io/repos/github/it-bens/object-transformer/badge.svg?branch=master)](https://coveralls.io/github/it-bens/object-transformer?branch=master)

## How to install the package?
The package can be installed via Composer:
```bash
composer require it-bens/object-transformer
```
It requires at least PHP 8, but no other extensions or packages.

## How to use the Object Transformer?
First, at least one implementation of the `TransformerInterface` has to be created.
```php
use ITB\ObjectTransformer\TransformerInterface;

class OptimusPrime implements TransformerInterface 
{
    public static function supportedTransformations(): array
    {
        return [['input' => MissionCity::class, 'output' => Ruins::class]];
    }
    
    public function transform(TransformationEnvelope $envelope, string $outputClassName): object
    {
        // This method performs the actual transformation and returns the resulting object.
    }    
}

class Megatron implements TransformerInterface 
{
    public static function supportedTransformations(): array
    {
        return [['input' => SamWitwicky::class, 'output' => Corpse::class]];
    }
    
    public function transform(TransformationEnvelope $envelope, string $outputClassName): object {...}
}
```
The input- and output class names are used to register the supported transformations in the `TransformationMediator`.

The `TransformationMediator` requires an iterable of `TransfromerInterface`-implementing objects (at least one).
```php
use ITB\ObjectTransformer\TransformationMediator;

$mediator = new TransformationMediator(new \ArrayObject([new OptimusPrime(), new Megatron()]));
```

Because the `TransformerInterface` objects are processed at the first call of the `transform` method of the `TransformationMediator`,
the `TranformationMediator` (or the `TransformationMediatorInterface`) can be passed as constructor argument to the transformer 
(otherwise, this could lead to endless circle calls).

After everything is prepared, the `transform` method can be used.
```php
$object1 = new Object1('The hell am I doing here?');
$object2 = $mediator->transform($object1, Object2::class);

// Explicit envelope usage
$object2 = $mediator->transform(TransformationEnvelope::wrap($object1), Object2::class);
```
The `transform` method of the `TransformationMediator` can handle any object. If the passed object isn't a `TransformationEnvelope`,
it will be wrapped with one. So if you don't want to use any stamps (see below), just pass the ordinary object.
But be aware, that you will always receive a `TransformationEnvelope` in your implementations of `TransformerInterface?`.

## What do you mean with "stamp"?
The envelope and stamp system is inspired, but not identical to the system used by the Symfony messenger component.
Every object that is passed to the `transform` method of the `TransformationMediator` is wrapped with an `TransformationEnvelope`
(if it's not already one).

Like a real envelope, the `TransformationEnvelope` can carry stamps. Stamps have two tasks:
1. pass data to the `transform` method of the `TransformerInterface` implementation
2. provide data that can be used during processing in the `TransformationMediator`

All stamps have to implement the `TransformationStampInterface` and provide a priority.

The main difference to the Symfony messenger component is, that the envelope can only hold one stamp per type.
If two or more stamps of the same type are passed to the envelope, a later stamp will overwrite an earlier one, 
if it's priority is higher.

### Looping data through the mediator
To loop data through the mediator to the transformer, any custom stamp can be passed to the envelope. 
They won't be touched during processing and are accessible via the envelope:
```php
public function transform(TransformationEnvelope $envelope, string $outputClassName): object
{
    $customStamp = $envelope->getStamp(CustomStampClass::class); // returns null if the envelope contains no such stamp
}   
```

### Data processed by the mediator
All implementations of the `TransformationStampInterface` provided by this package are used inside the mediator.
After there usage they are removed from the envelope and are not accessible in the `TransformerInface` implementation.

#### InputClassStamp
Because of its internal data flow, the passed input object has to be of the exact same class
that was defined as input in the `supportedTransformations` method of the factory.

This could lead to problems with packages like Doctrine. Doctrine creates proxy classes for managed entities,
that can be used just like the Entity itself. However, the `TransformationMediator` would not find a matching transformer
and throw an exception, because the exact class of the proxy object is not registered for transformation.

That's where the `InputClassStamp` comes into play. Let's define some objects first.
```php
class Object1
{
    public $someString;
    public function __construct($someString) { $this->someString = $someString; }
}

class Object2
{
    public $letterCount;
    public function __construct($letterCount) { $this->letterCount = $letterCount; }
}

class Object3 extends Object1
{
}
```
The following lines would lead to an `UnsupportedInputOutputTypes` exception.
```php
$object3 = new Object3('The hell am I doing here?');
$result = $mediator->tranform($object3, Object2::class);
```
With the `InputClassStamp` it's working.
```php
use ITB\ObjectTransformer\TransformationMediator;

$envelope = new \ITB\ObjectTransformer\TransformationEnvelope(
    new Object3('The hell am I doing here?'),
    [new InputClassStamp(Object1::class)]
);
$result = $mediator->tranform($envelope, Object2::class);
```

## Why does this package exist?
A common pattern I stumbled across in my projects is to map data between different objects types like DTOs and Entities.

The mapping is often very simple: the value of the DTO (Data Transfer Object) property 
is the same as the property of the Entity (and vice versa).
However, things get more complicated if value objects are used (which I strongly recommend).
New objects has to be created and the few lines of code get more and more complex.

In my projects, this often lead to the creation of factory classes, that handle the object transformation 
and contain as little business logic as possible. But, as a class should only serve a single purpose, 
there can be a lot of such factories. When engineering more complex entities, the factories sometimes depend on each other.
That makes the DI (Dependency Injection) or Singleton usage quite a mess.

That's where the object transformer comes into play: the mediator is sufficient to do all the transformations needed.

## How does the Object Transformer work?
A factory or any other class that should be used by the `TransformationMediator` 
has to implement the `TransformerInterface`.

All classes that implement the interface (and are passed to the mediator) provide an array of supported transformations
via the static `supportedTransformations` method. The array contains an array for every supported transformation.
Every one of these inner arrays requires an `input` and an `ouput` key, which both represent existing classes.

When the `transform` method of the `TransformationMediator` is first called, it will populate it's internal transformer registry.
For performance reasons every supported transformation is registered with it's input and output class.
This way, the associations can be used to find a responsible transformer.

## Contributing
I am really happy that the software developer community loves Open Source, like I do! ♥

That's why I appreciate every issue that is opened (preferably constructive) 
and every pull request that provides other or even better code to this package.

You are all breathtaking!