<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\tests\parts;

use gossi\codegen\model\PhpClass;

trait ModelAssertions {
	private function assertClassWithValues(PhpClass $class): void {
		$bar = $class->getProperty('bar');
		$this->assertFalse($bar->getValue());
		$this->assertTrue($bar->hasValue());
		$this->assertNull($bar->getExpression());

		$magic = $class->getProperty('magic');
		$this->assertNull($magic->getValue());
		$this->assertTrue($magic->isExpression());
		$this->assertEquals('__LINE__', $magic->getExpression());

		$null = $class->getProperty('null');
		$this->assertFalse($null->isExpression());
		$this->assertNull($null->getValue());

		$arr = $class->getProperty('arr');
		$this->assertEquals("['papagei' => ['name' => 'Mr. Cottons Papagei']]", $arr->getExpression());
	}

	private function assertClassWithComments(PhpClass $class): void {
		$docblock = $class->getDocblock();

		$this->assertEquals($docblock->getShortDescription(), $class->getDescription());
		$this->assertEquals($docblock->getLongDescription(), $class->getLongDescription());

		$this->assertEquals('A class with comments', $docblock->getShortDescription());
		$this->assertEquals('Here is a super dooper long-description', $docblock->getLongDescription());

		$this->assertTrue($docblock->getTags('author')->size() > 0);
		$this->assertTrue($docblock->getTags('since')->size() > 0);

		$FOO = $class->getConstant('FOO');

		$this->assertEquals('Best const ever', $FOO->getDescription());
		$this->assertEquals('Aaaand we go along long', $FOO->getLongDescription());
		$this->assertEquals('baz', $FOO->getTypeDescription());
		$this->assertEquals('string', $FOO->getType());
		$this->assertEquals('bar', $FOO->getValue());

		$propper = $class->getProperty('propper');

		$this->assertEquals('Best prop ever', $propper->getDescription());
		$this->assertEquals('Aaaand we go along long long', $propper->getLongDescription());
		$this->assertEquals('Wer macht sauber?', $propper->getTypeDescription());
		$this->assertEquals('string', $propper->getType());
		$this->assertEquals('Meister', $propper->getValue());

		$setup = $class->getMethod('setup');
		$this->assertEquals('Short desc', $setup->getDescription());
		$this->assertEquals('Looong desc', $setup->getLongDescription());
		$this->assertEquals('true on success and false if it fails', $setup->getTypeDescription());
		$this->assertEquals('bool', $setup->getType());

		$moo = $setup->getParameter('moo');
		$this->assertEquals('makes a cow', $moo->getTypeDescription());
		$this->assertEquals('bool', $moo->getType());

		$foo = $setup->getParameter('foo');
		$this->assertEquals('makes a fow', $foo->getTypeDescription());
		$this->assertEquals('string', $foo->getType());
	}

	public function assertInferClass(PhpClass $class): void {
		$stringProperty = $class->getProperty('stringProperty');
		$this->assertEquals('string', $stringProperty->getType());
		$this->assertEquals('', $stringProperty->getValue());
		$this->assertEquals('private', $stringProperty->getVisibility());

		$mixedProperty = $class->getProperty('mixedProperty');
		$this->assertEquals('mixed', $mixedProperty->getType());
		$this->assertNull($mixedProperty->getValue());
		$this->assertEquals('private', $mixedProperty->getVisibility());

		$iteratorProperty = $class->getProperty('iterator');
		$this->assertEquals('\IteratorAggregate', $iteratorProperty->getType());
		$this->assertNull($iteratorProperty->getValue());
		$this->assertEquals('protected', $iteratorProperty->getVisibility());

		$textProperty = $class->getProperty('text');
		$this->assertEquals('string|Text', $textProperty->getType());
		$this->assertNull($textProperty->getValue());
		$this->assertEquals('public', $textProperty->getVisibility());

		$method = $class->getMethod('manipulate');
		$this->assertEquals('public', $method->getVisibility());
		$this->assertEquals('string', $method->getType());

		$param1 = $method->getParameter('firstParameter');
		$this->assertEquals('string', $param1->getType());
		$this->assertNull($param1->getValue());
		$this->assertTrue($param1->getNullable());

		$param2 = $method->getParameter('secondParameter');
		$this->assertEquals('mixed', $param2->getType());
		$this->assertNull($param2->getValue());
		//Even if `mixed` type can contain null values, the parameter isn't nullable
		//in the sense that we can't write `?mixed`
		$this->assertFalse($param2->getNullable());

		$mixedMethod = $class->getMethod('getMixed');
		$this->assertEquals('private', $mixedMethod->getVisibility());
		$this->assertEquals('mixed', $mixedMethod->getType());

		$intParam = $mixedMethod->getParameter('param');
		$this->assertEquals('int', $intParam->getType());
		$this->assertEquals(1, $intParam->getValue());
		$this->assertFalse($intParam->getNullable());
	}
}
