<?php

declare(strict_types=1);

namespace ITB\ObjectTransformer\Tests;

use ITB\ObjectTransformer\Exception\NotATransformationStamp;
use ITB\ObjectTransformer\Stamp\InputClassStamp;
use ITB\ObjectTransformer\Tests\Mock\AdditionalDataStamp;
use ITB\ObjectTransformer\Tests\Mock\Object1;
use ITB\ObjectTransformer\Tests\Mock\Object2;
use ITB\ObjectTransformer\Tests\Mock\Object3;
use ITB\ObjectTransformer\TransformationEnvelope;
use PHPUnit\Framework\TestCase;

final class TransformationEnvelopeTest extends TestCase
{
    public function testGetStamp(): void
    {
        $input = new Object1('I\'m Mr. Meeseeks, look at me!');
        $stamp1 = new InputClassStamp(Object3::class);
        $stamp2 = new AdditionalDataStamp(['what' => 'the hell?']);
        $envelope = TransformationEnvelope::wrap($input, [$stamp1, $stamp2]);

        $testInputClassStamp = $envelope->getStamp(InputClassStamp::class);
        $this->assertInstanceOf(InputClassStamp::class, $testInputClassStamp);
        $this->assertEquals($stamp1, $testInputClassStamp);

        $testAdditionalDataStamp = $envelope->getStamp(AdditionalDataStamp::class);
        $this->assertInstanceOf(AdditionalDataStamp::class, $testAdditionalDataStamp);
        $this->assertEquals($stamp2, $testAdditionalDataStamp);
    }

    public function testRemoveStamp(): void
    {
        $input = new Object1('I\'m Mr. Meeseeks, look at me!');
        $stamp = new InputClassStamp(Object3::class);
        $envelope = TransformationEnvelope::wrap($input, [$stamp]);
        $envelope->removeStamp(InputClassStamp::class);

        $this->assertCount(0, $envelope->getStamps());
        $this->assertEquals(null, $envelope->getStamp(InputClassStamp::class));
    }

    public function testWrapEnvelope(): void
    {
        $input = new Object1('I\'m Mr. Meeseeks, look at me!');
        $envelope = TransformationEnvelope::wrap(new TransformationEnvelope($input));

        $this->assertEquals($input, $envelope->getInput());
        $this->assertEquals([], $envelope->getStamps());
    }

    public function testWrapInput(): void
    {
        $input = new Object1('I\'m Mr. Meeseeks, look at me!');
        $envelope = TransformationEnvelope::wrap($input);

        $this->assertEquals($input, $envelope->getInput());
        $this->assertEquals([], $envelope->getStamps());
    }

    public function testWrapNotOverrideStamp(): void
    {
        $input = new Object1('I\'m Mr. Meeseeks, look at me!');
        $stamp1 = new InputClassStamp(Object1::class, 0);
        $stamp2 = new InputClassStamp(Object3::class, 0);
        $envelope = TransformationEnvelope::wrap($input, [$stamp1, $stamp2]);

        $this->assertCount(1, $envelope->getStamps());
        $this->assertEquals($stamp1, $envelope->getStamp(InputClassStamp::class));
    }

    public function testWrapOverrideStamp(): void
    {
        $input = new Object1('I\'m Mr. Meeseeks, look at me!');
        $inputClassStamp1 = new InputClassStamp(Object1::class, 0);
        $inputClassStamp2 = new InputClassStamp(Object3::class, 1);
        $additionalDataStamp1 = new AdditionalDataStamp(['test' => 1], 0);
        $additionalDataStamp2 = new AdditionalDataStamp(['test' => 2], 1);
        $envelope = TransformationEnvelope::wrap($input, [
            $inputClassStamp1,
            $inputClassStamp2,
            $additionalDataStamp1,
            $additionalDataStamp2
        ]);

        $this->assertCount(2, $envelope->getStamps());
        $this->assertEquals($inputClassStamp2, $envelope->getStamp(InputClassStamp::class));
        $this->assertEquals($additionalDataStamp2, $envelope->getStamp(AdditionalDataStamp::class));
    }

    public function testWrapWithDifferentStamps(): void
    {
        $input = new Object1('I\'m Mr. Meeseeks, look at me!');
        $stamp1 = new InputClassStamp(Object3::class);
        $stamp2 = new AdditionalDataStamp(['what' => 'the hell?']);
        $envelope = TransformationEnvelope::wrap($input, [$stamp1, $stamp2]);

        $this->assertEquals($input, $envelope->getInput());
        $this->assertCount(2, $envelope->getStamps());
    }

    public function testWrapWithInvalidStamp(): void
    {
        $input = new Object1('I\'m Mr. Meeseeks, look at me!');
        $stamp = new Object2(0);

        $this->expectException(NotATransformationStamp::class);
        /** @phpstan-ignore-next-line */
        TransformationEnvelope::wrap($input, [$stamp]);
    }

    public function testWrapWithoutStamps(): void
    {
        $input = new Object1('I\'m Mr. Meeseeks, look at me!');
        $envelope = TransformationEnvelope::wrap($input);

        $this->assertEquals($input, $envelope->getInput());
        $this->assertEquals([], $envelope->getStamps());
    }
}
