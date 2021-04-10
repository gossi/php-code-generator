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
use PHPUnit\Framework\TestCase;

/**
 * @group parser
 */
class ParameterParserTest extends TestCase {
	public function testValuesFromFile(): void {
		$class = PhpClass::fromFile(__DIR__ . '/../fixtures/ValueClass.php');
		$this->assertValueClass($class);
	}

	protected function assertValueClass(PhpClass $class): void {
		$values = $class->getMethod('values');
		$this->assertIsString($values->getParameter('paramString')->getValue());
		$this->assertIsInt($values->getParameter('paramInteger')->getValue());
		$this->assertIsFloat($values->getParameter('paramFloat')->getValue());
		$this->assertIsBool($values->getParameter('paramBool')->getValue());
		$this->assertNull($values->getParameter('paramNull')->getValue());
		$this->assertEquals('self::CONST_STRING', $values->getParameter('paramConst')->getExpression());
	}
}
