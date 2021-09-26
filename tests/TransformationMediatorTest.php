<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer\Tests;

use ArrayObject;
use ITB\ObjectTransformer\Exception\InvalidAdditionalData;
use ITB\ObjectTransformer\Exception\InvalidInputObject;
use ITB\ObjectTransformer\Exception\InvalidOutputClassName;
use ITB\ObjectTransformer\Exception\InvalidTransformerConfiguration;
use ITB\ObjectTransformer\Exception\NoTransformers;
use ITB\ObjectTransformer\Exception\UnsupportedInputOutputTypes;
use ITB\ObjectTransformer\TransformationMediator;
use ITB\ObjectTransformerTestUtilities\DummyTransformer;
use ITB\ObjectTransformerTestUtilities\InvalidDummyTransformerInputNotClass;
use ITB\ObjectTransformerTestUtilities\InvalidDummyTransformerInputNotString;
use ITB\ObjectTransformerTestUtilities\InvalidDummyTransformerNoInput;
use ITB\ObjectTransformerTestUtilities\InvalidDummyTransformerNoOutput;
use ITB\ObjectTransformerTestUtilities\InvalidDummyTransformerOutputNotClass;
use ITB\ObjectTransformerTestUtilities\InvalidDummyTransformerOutputNotString;
use ITB\ObjectTransformerTestUtilities\Object1;
use ITB\ObjectTransformerTestUtilities\Object2;
use ITB\ObjectTransformerTestUtilities\Object3;
use PHPUnit\Framework\TestCase;

final class TransformationMediatorTest extends TestCase
{
    /** @var TransformationMediator $mediator */
    private TransformationMediator $mediator;

    public function setUp(): void
    {
        $transformer = [new DummyTransformer()];
        $this->mediator = new TransformationMediator(new ArrayObject($transformer));
    }

    public function testTransform(): void
    {
        $object1 = new Object1('I\'m Mr. Meeseeks, look at me!');
        $result = $this->mediator->transform($object1, Object2::class);

        $this->assertInstanceOf(Object2::class, $result);
        $this->assertEquals(strlen($object1->someString), $result->letterCount);
    }

    public function testTransformExplicitInputClassName(): void
    {
        $object1 = new Object3('I\'m Mr. Meeseeks, look at me!');
        $result = $this->mediator->transform($object1, Object2::class, ['inputClassName' => Object1::class]);

        $this->assertInstanceOf(Object2::class, $result);
        $this->assertEquals(strlen($object1->someString), $result->letterCount);
    }

    public function testTransformInvalidInputType(): void
    {
        $object2 = new Object2(2);

        $this->expectException(UnsupportedInputOutputTypes::class);
        $this->mediator->transform($object2, Object1::class);
    }

    public function testTransformWithoutTransformers(): void
    {
        $mediator = new TransformationMediator(new ArrayObject());
        $object1 = new Object1('I\'m Mr. Meeseeks, look at me!');

        $this->expectException(NoTransformers::class);
        $mediator->transform($object1, Object2::class);
    }

    public function testTransformInvalidInputObjectNotObject(): void
    {
        $this->expectException(InvalidInputObject::class);
        $this->mediator->transform(1337, Object2::class);
    }

    public function testTransformInvalidOutputClassNameNotString(): void
    {
        $object1 = new Object1('I\'m Mr. Meeseeks, look at me!');

        $this->expectException(InvalidOutputClassName::class);
        $this->mediator->transform($object1, 42);
    }

    public function testTransformInvalidOutputClassNameEmptyString(): void
    {
        $object1 = new Object1('I\'m Mr. Meeseeks, look at me!');

        $this->expectException(InvalidOutputClassName::class);
        $this->mediator->transform($object1, '');
    }

    public function testTransformInvalidAdditionalDataNotArray(): void
    {
        $object1 = new Object1('I\'m Mr. Meeseeks, look at me!');

        $this->expectException(InvalidAdditionalData::class);
        $this->mediator->transform($object1, Object2::class, 'I\'m in danger');
    }

    public function testTransformInvalidTransformerConfigurationNoInput(): void
    {
        $mediator = new TransformationMediator(new ArrayObject([new InvalidDummyTransformerNoInput()]));
        $object1 = new Object1('I\'m Mr. Meeseeks, look at me!');

        $this->expectException(InvalidTransformerConfiguration::class);
        $mediator->transform($object1, Object2::class);
    }

    public function testTransformInvalidTransformerConfigurationInputNotString(): void
    {
        $mediator = new TransformationMediator(new ArrayObject([new InvalidDummyTransformerInputNotString()]));
        $object1 = new Object1('I\'m Mr. Meeseeks, look at me!');

        $this->expectException(InvalidTransformerConfiguration::class);
        $mediator->transform($object1, Object2::class);
    }

    public function testTransformInvalidTransformerConfigurationInputNotClass(): void
    {
        $mediator = new TransformationMediator(new ArrayObject([new InvalidDummyTransformerInputNotClass()]));
        $object1 = new Object1('I\'m Mr. Meeseeks, look at me!');

        $this->expectException(InvalidTransformerConfiguration::class);
        $mediator->transform($object1, Object2::class);
    }

    public function testTransformInvalidTransformerConfigurationNoOutput(): void
    {
        $mediator = new TransformationMediator(new ArrayObject([new InvalidDummyTransformerNoOutput()]));
        $object1 = new Object1('I\'m Mr. Meeseeks, look at me!');

        $this->expectException(InvalidTransformerConfiguration::class);
        $mediator->transform($object1, Object2::class);
    }

    public function testTransformInvalidTransformerConfigurationOutputNotString(): void
    {
        $mediator = new TransformationMediator(new ArrayObject([new InvalidDummyTransformerOutputNotString()]));
        $object1 = new Object1('I\'m Mr. Meeseeks, look at me!');

        $this->expectException(InvalidTransformerConfiguration::class);
        $mediator->transform($object1, Object2::class);
    }

    public function testTransformInvalidTransformerConfigurationOutputNotClass(): void
    {
        $mediator = new TransformationMediator(new ArrayObject([new InvalidDummyTransformerOutputNotClass()]));
        $object1 = new Object1('I\'m Mr. Meeseeks, look at me!');

        $this->expectException(InvalidTransformerConfiguration::class);
        $mediator->transform($object1, Object2::class);
    }
}
