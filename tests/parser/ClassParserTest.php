<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\tests\parser;

use gossi\codegen\model\PhpClass;
use gossi\codegen\tests\Fixtures;
use gossi\codegen\tests\parts\ModelAssertions;
use PHPUnit\Framework\TestCase;

/**
 * @group parser
 */
class ClassParserTest extends TestCase {
	use ModelAssertions;

	public function testEntity(): void {
		$class = PhpClass::fromFile(__DIR__ . '/../fixtures/Entity.php');

		$this->assertEquals(Fixtures::createEntity(), $class);
	}

	public function testMethodBody(): void {
		$class = PhpClass::fromFile(__DIR__ . '/../fixtures/HelloWorld.php');

		$this->assertTrue($class->hasMethod('sayHello'));

		$sayHello = $class->getMethod('sayHello');
		$this->assertEquals('return \'Hello World!\';', $sayHello->getBody());
	}

	public function testClassWithConstants(): void {
		$class = PhpClass::fromFile(__DIR__ . '/../fixtures/ClassWithConstants.php');

		$this->assertTrue($class->hasConstant('FOO'));
		$this->assertEquals('bar', $class->getConstant('FOO')->getValue());

		$this->assertTrue($class->hasConstant('NMBR'));
		$this->assertEquals(300, $class->getConstant('NMBR')->getValue());

		$this->assertTrue($class->hasConstant('BAR'));
		$this->assertEquals('self::FOO', $class->getConstant('BAR')->getExpression());
	}

	public function testClassWithTraits(): void {
		$class = PhpClass::fromFile(__DIR__ . '/../fixtures/ClassWithTraits.php');

		$this->assertTrue($class->hasTrait('DT'));
	}

	public function testClassWithComments(): void {
		$class = PhpClass::fromFile(__DIR__ . '/../fixtures/ClassWithComments.php');
		$this->assertClassWithComments($class);
	}

	public function testClassWithValues(): void {
		$class = PhpClass::fromFile(__DIR__ . '/../fixtures/ClassWithValues.php');
		$this->assertClassWithValues($class);
	}

	public function testTypeClass(): void {
		$class = PhpClass::fromFile(__DIR__ . '/../fixtures/TypeClass.php');

		$doSomething = $class->getMethod('doSomething');
		$options = $doSomething->getParameter('options');
		$this->assertEquals('Symfony\Component\OptionsResolver\OptionsResolver', $options->getType());
	}

	public function testMyCollection(): void {
		$class = PhpClass::fromFile(__DIR__ . '/../fixtures/MyCollection.php');

		$this->assertEquals('phootwork\collection\AbstractCollection', $class->getParentClassName());
		$this->assertTrue($class->hasInterface('phootwork\collection\Collection'));
	}

	public function testMyCollection2(): void {
		$class = PhpClass::fromFile(__DIR__ . '/../fixtures/MyCollection2.php');

		$this->assertEquals('\phootwork\collection\AbstractCollection', $class->getParentClassName());
		$this->assertTrue($class->hasInterface('\phootwork\collection\Collection'));
	}

	public function testInferTypesFromCode(): void {
		$class = PhpClass::fromFile(__DIR__ . '/../fixtures/InferClass.php');
		$this->assertInferClass($class);
	}
}
