<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\tests\model;

use gossi\codegen\model\PhpProperty;
use PHPUnit\Framework\TestCase;

/**
 * @group model
 */
class PropertyTest extends TestCase {
	public function testSetGetValue(): void {
		$prop = new PhpProperty('needsName');

		$this->assertNull($prop->getValue());
		$this->assertFalse($prop->hasValue());
		$this->assertSame($prop, $prop->setValue('foo'));
		$this->assertEquals('foo', $prop->getValue());
		$this->assertTrue($prop->hasValue());
		$this->assertSame($prop, $prop->unsetValue());
		$this->assertNull($prop->getValue());
		$this->assertFalse($prop->hasValue());
	}

	public function testSetGetExpression(): void {
		$prop = new PhpProperty('needsName');

		$this->assertNull($prop->getExpression());
		$this->assertFalse($prop->isExpression());
		$this->assertSame($prop, $prop->setExpression('null'));
		$this->assertEquals('null', $prop->getExpression());
		$this->assertTrue($prop->isExpression());
		$this->assertSame($prop, $prop->unsetExpression());
		$this->assertNull($prop->getExpression());
		$this->assertFalse($prop->isExpression());
	}

	public function testValueAndExpression(): void {
		$prop = new PhpProperty('needsName');

		$prop->setValue('abc');
		$prop->setExpression('null');

		$this->assertTrue($prop->hasValue());
		$this->assertTrue($prop->isExpression());
	}

	public function testValues(): void {
		$this->assertIsString(PhpProperty::create('x')->setValue('hello')->getValue());
		$this->assertIsInt(PhpProperty::create('x')->setValue(2)->getValue());
		$this->assertIsFloat(PhpProperty::create('x')->setValue(0.2)->getValue());
		$this->assertIsBool(PhpProperty::create('x')->setValue(false)->getValue());
		$this->assertNull(PhpProperty::create('x')->setValue(null)->getValue());
	}

	public function testInvalidValue(): void {
		$this->expectException(\TypeError::class);

		PhpProperty::create('x')->setValue(new \stdClass());
	}
}
