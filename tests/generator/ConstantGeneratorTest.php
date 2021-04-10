<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\tests\generator;

use gossi\codegen\generator\CodeGenerator;
use gossi\codegen\model\PhpConstant;
use PHPUnit\Framework\TestCase;

/**
 * @group generator
 */
class ConstantGeneratorTest extends TestCase {
	public function testValues(): void {
		$generator = new CodeGenerator(['generateEmptyDocblock' => false]);

		$prop = PhpConstant::create('FOO')->setValue('string');
		$this->assertEquals('	const FOO = \'string\';', $generator->generate($prop));

		$prop = PhpConstant::create('FOO')->setValue(300);
		$this->assertEquals('	const FOO = 300;', $generator->generate($prop));

		$prop = PhpConstant::create('FOO')->setValue(162.5);
		$this->assertEquals('	const FOO = 162.5;', $generator->generate($prop));

		$prop = PhpConstant::create('FOO')->setValue(true);
		$this->assertEquals('	const FOO = true;', $generator->generate($prop));

		$prop = PhpConstant::create('FOO')->setValue(false);
		$this->assertEquals('	const FOO = false;', $generator->generate($prop));

		$prop = PhpConstant::create('FOO')->setValue(null);
		$this->assertEquals('	const FOO = null;', $generator->generate($prop));

		$prop = PhpConstant::create('FOO')->setValue(PhpConstant::create('BAR'));
		$this->assertEquals('	const FOO = BAR;', $generator->generate($prop));

		$prop = PhpConstant::create('FOO')->setExpression("['bar' => 'baz']");
		$this->assertEquals('	const FOO = [\'bar\' => \'baz\'];', $generator->generate($prop));
	}
}
