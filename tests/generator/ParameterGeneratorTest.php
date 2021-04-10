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
use gossi\codegen\model\GenerateableInterface;
use gossi\codegen\model\PhpConstant;
use gossi\codegen\model\PhpParameter;
use PHPUnit\Framework\TestCase;

/**
 * @group generator
 */
class ParameterGeneratorTest extends TestCase {
	public function testPassedByReference(): void {
		$expected = '&$foo';

		$param = TestablePhpParameter::create('foo')->setPassedByReference(true);
		$generator = new CodeGenerator();
		$code = $generator->generate($param);

		$this->assertEquals($expected, $code);
	}

	public function testTypeHints(): void {
		$generator = new CodeGenerator();

		$param = TestablePhpParameter::create('foo')->setType('Request');
		$this->assertEquals('Request $foo', $generator->generate($param));
	}

	public function testNoTypeHints(): void {
		$generator = new CodeGenerator(['generateTypeHints' => false]);

		$param = TestablePhpParameter::create('foo')->setType('string');
		$this->assertEquals('$foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('int');
		$this->assertEquals('$foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('integer');
		$this->assertEquals('$foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('float');
		$this->assertEquals('$foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('double');
		$this->assertEquals('$foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('bool');
		$this->assertEquals('$foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('boolean');
		$this->assertEquals('$foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('mixed');
		$this->assertEquals('$foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('object');
		$this->assertEquals('$foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('resource');
		$this->assertEquals('$foo', $generator->generate($param));
	}

	public function testPhp74TypeHints(): void {
		$generator = new CodeGenerator(['minPhpVersion' => '7.4']);

		$param = TestablePhpParameter::create('foo')->setType('string');
		$this->assertEquals('string $foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('int');
		$this->assertEquals('int $foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('integer');
		$this->assertEquals('int $foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('float');
		$this->assertEquals('float $foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('double');
		$this->assertEquals('float $foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('bool');
		$this->assertEquals('bool $foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('boolean');
		$this->assertEquals('bool $foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('mixed');
		$this->assertEquals('$foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('object');
		$this->assertEquals('object $foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('resource');
		$this->assertEquals('resource $foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('float')->setNullable(true);
		$this->assertEquals('?float $foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('Class1|Class2|null');
		$this->assertEquals('$foo', $generator->generate($param));
	}

	public function testPhp8TypeHints(): void {
		$generator = new CodeGenerator();

		$param = TestablePhpParameter::create('foo')->setType('string');
		$this->assertEquals('string $foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('int');
		$this->assertEquals('int $foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('integer');
		$this->assertEquals('int $foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('float');
		$this->assertEquals('float $foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('double');
		$this->assertEquals('float $foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('bool');
		$this->assertEquals('bool $foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('boolean');
		$this->assertEquals('bool $foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('mixed');
		$this->assertEquals('mixed $foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('mixed')->setNullable(true);
		$this->assertEquals('mixed $foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('object');
		$this->assertEquals('object $foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('resource');
		$this->assertEquals('resource $foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('float')->setNullable(true);
		$this->assertEquals('?float $foo', $generator->generate($param));

		$param = TestablePhpParameter::create('foo')->setType('Class1|Class2|null');
		$this->assertEquals('Class1|Class2|null $foo', $generator->generate($param));
	}

	public function testValues(): void {
		$generator = new CodeGenerator();

		$prop = TestablePhpParameter::create('foo')->setValue('string');
		$this->assertEquals('$foo = \'string\'', $generator->generate($prop));

		$prop = TestablePhpParameter::create('foo')->setValue(300);
		$this->assertEquals('$foo = 300', $generator->generate($prop));

		$prop = TestablePhpParameter::create('foo')->setValue(162.5);
		$this->assertEquals('$foo = 162.5', $generator->generate($prop));

		$prop = TestablePhpParameter::create('foo')->setValue(true);
		$this->assertEquals('$foo = true', $generator->generate($prop));

		$prop = TestablePhpParameter::create('foo')->setValue(false);
		$this->assertEquals('$foo = false', $generator->generate($prop));

		$prop = TestablePhpParameter::create('foo')->setValue(null);
		$this->assertEquals('$foo = null', $generator->generate($prop));

		$prop = TestablePhpParameter::create('foo')->setValue(PhpConstant::create('BAR'));
		$this->assertEquals('$foo = BAR', $generator->generate($prop));

		$prop = TestablePhpParameter::create('foo')->setExpression("['bar' => 'baz']");
		$this->assertEquals('$foo = [\'bar\' => \'baz\']', $generator->generate($prop));
	}
}

class TestablePhpParameter extends PhpParameter implements GenerateableInterface {
	public function generateDocblock(): void {
	}
}
