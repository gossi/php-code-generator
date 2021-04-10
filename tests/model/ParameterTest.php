<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\tests\model;

use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpParameter;
use PHPUnit\Framework\TestCase;

/**
 * @group model
 */
class ParameterTest extends TestCase {
	public function testByReference(): void {
		$param = new PhpParameter();
		$this->assertFalse($param->isPassedByReference());
		$param->setPassedByReference(true);
		$this->assertTrue($param->isPassedByReference());
		$param->setPassedByReference(false);
		$this->assertFalse($param->isPassedByReference());
	}

	public function testType(): void {
		$param = new PhpParameter();

		$this->assertEquals('', $param->getType());
		$this->assertSame($param, $param->setType('array'));
		$this->assertEquals('array', $param->getType());
		$this->assertSame($param, $param->setType('array', 'boo!'));
		$this->assertEquals('boo!', $param->getTypeDescription());
	}

	public function testSimpleParameter(): void {
		$function = new PhpMethod('myMethod');
		$function->addSimpleParameter('param1', 'string');

		$this->assertTrue($function->hasParameter('param1'));
		$this->assertFalse($function->hasParameter('param2'));
		$param1 = $function->getParameter('param1');
		$this->assertEquals('string', $param1->getType());
		$this->assertFalse($param1->hasValue());

		$function->addSimpleParameter('param2', 'string', null);

		$this->assertTrue($function->hasParameter('param2'));
		$param2 = $function->getParameter('param2');
		$this->assertEquals('string', $param2->getType());
		$this->assertNull($param2->getValue());
	}

	public function testSimpleDescParameter(): void {
		$function = new PhpMethod('myMethod');
		$function->addSimpleDescParameter('param1', 'string');

		$this->assertFalse($function->hasParameter('param2'));
		$param1 = $function->getParameter('param1');
		$this->assertEquals('string', $param1->getType());
		$this->assertFalse($param1->hasValue());

		$function->addSimpleDescParameter('param2', 'string', 'desc');

		$this->assertTrue($function->hasParameter('param2'));
		$param2 = $function->getParameter('param2');
		$this->assertEquals('string', $param2->getType());
		$this->assertFalse($param2->hasValue());

		$function->addSimpleDescParameter('param3', 'string', 'desc', null);

		$this->assertTrue($function->hasParameter('param3'));
		$param3 = $function->getParameter('param3');
		$this->assertEquals('string', $param3->getType());
		$this->assertNull($param3->getValue());
	}

	public function testValues(): void {
		$this->assertIsString(PhpParameter::create()->setValue('hello')->getValue());
		$this->assertIsInt(PhpParameter::create()->setValue(2)->getValue());
		$this->assertIsFloat(PhpParameter::create()->setValue(0.2)->getValue());
		$this->assertIsBool(PhpParameter::create()->setValue(false)->getValue());
		$this->assertNull(PhpParameter::create()->setValue(null)->getValue());
	}
}
