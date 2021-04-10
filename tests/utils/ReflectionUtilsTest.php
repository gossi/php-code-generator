<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\tests\utils;

use gossi\codegen\utils\ReflectionUtils;
use PHPUnit\Framework\TestCase;

class ReflectionUtilsTest extends TestCase {
	public function setUp(): void {
		// they are not explicitely instantiated through new WhatEver(); and such not
		// required through composer's autoload
		require_once __DIR__ . '/../fixtures/functions.php';
		require_once __DIR__ . '/../fixtures/OverridableReflectionTest.php';
	}

	public function testFunctionBody(): void {
		$actual = ReflectionUtils::getFunctionBody(new \ReflectionFunction('wurst'));
		$expected = 'return \'wurst\';';

		$this->assertEquals($expected, $actual);

		$actual = ReflectionUtils::getFunctionBody(new \ReflectionFunction('inline'));
		$expected = 'return \'x\';';

		$this->assertEquals($expected, $actual);
	}

	public function testGetOverridableMethods(): void {
		$ref = new \ReflectionClass('gossi\codegen\tests\fixtures\OverridableReflectionTest');
		$methods = ReflectionUtils::getOverrideableMethods($ref);

		$this->assertEquals(4, count($methods));

		$methods = array_map(function ($v) {
			return $v->name;
		}, $methods);
		sort($methods);
		$this->assertEquals([
			'a',
			'd',
			'e',
			'h'
		], $methods);
	}

	public function testGetUnindentedDocComment(): void {
		$comment = "/**\n\t * Foo.\n\t */";

		$this->assertEquals("/**\n * Foo.\n */", ReflectionUtils::getUnindentedDocComment($comment));
	}
}
