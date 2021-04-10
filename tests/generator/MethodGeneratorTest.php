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
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpParameter;
use PHPUnit\Framework\TestCase;

/**
 * @group generator
 */
class MethodGeneratorTest extends TestCase {
	public function testPublic(): void {
		$expected = "\tpublic function foo() {\n\t}";

		$method = PhpMethod::create('foo');
		$generator = new CodeGenerator(['generateEmptyDocblock' => false]);
		$code = $generator->generate($method);

		$this->assertEquals($expected, $code);
	}

	public function testProtected(): void {
		$expected = "\tprotected function foo() {\n\t}";

		$method = PhpMethod::create('foo')->setVisibility(PhpMethod::VISIBILITY_PROTECTED);
		$generator = new CodeGenerator(['generateEmptyDocblock' => false]);
		$code = $generator->generate($method);

		$this->assertEquals($expected, $code);
	}

	public function testPrivate(): void {
		$expected = "\tprivate function foo() {\n\t}";

		$method = PhpMethod::create('foo')->setVisibility(PhpMethod::VISIBILITY_PRIVATE);
		$generator = new CodeGenerator(['generateEmptyDocblock' => false]);
		$code = $generator->generate($method);

		$this->assertEquals($expected, $code);
	}

	public function testStatic(): void {
		$expected = "\tpublic static function foo() {\n\t}";

		$method = PhpMethod::create('foo')->setStatic(true);
		$generator = new CodeGenerator(['generateEmptyDocblock' => false]);
		$code = $generator->generate($method);

		$this->assertEquals($expected, $code);
	}

	public function testAbstract(): void {
		$expected = "\tabstract public function foo();";

		$method = PhpMethod::create('foo')->setAbstract(true);
		$generator = new CodeGenerator(['generateEmptyDocblock' => false]);
		$code = $generator->generate($method);

		$this->assertEquals($expected, $code);
	}

	public function testReferenceReturned(): void {
		$expected = "\tpublic function & foo() {\n\t}";

		$method = PhpMethod::create('foo')->setReferenceReturned(true);
		$generator = new CodeGenerator(['generateEmptyDocblock' => false]);
		$code = $generator->generate($method);

		$this->assertEquals($expected, $code);
	}

	public function testParameters(): void {
		$generator = new CodeGenerator(['generateDocblock' => false]);

		$method = PhpMethod::create('foo')->addParameter(PhpParameter::create('bar'));
		$this->assertEquals("\tpublic function foo(\$bar) {\n\t}", $generator->generate($method));

		$method = PhpMethod::create('foo')
			->addParameter(PhpParameter::create('bar'))
			->addParameter(PhpParameter::create('baz'));
		$this->assertEquals("\tpublic function foo(\$bar, \$baz) {\n\t}", $generator->generate($method));
	}

	public function testReturnType(): void {
		$generator = new CodeGenerator(['generateDocblock' => false]);

		$method = PhpMethod::create('foo')->setType('int');
		$this->assertEquals("\tpublic function foo(): int {\n\t}", $generator->generate($method));

		$method = PhpMethod::create('foo')->setType('mixed');
		$this->assertEquals("\tpublic function foo(): mixed {\n\t}", $generator->generate($method));

		$method = PhpMethod::create('foo')->setType('Class1|Class2|string|null');
		$this->assertEquals("\tpublic function foo(): Class1|Class2|string|null {\n\t}", $generator->generate($method));
	}

	public function testNullableReturnType(): void {
		$generator = new CodeGenerator(['generateDocblock' => false]);

		$method = PhpMethod::create('foo')->setType('int')->setNullable(true);
		$this->assertEquals("\tpublic function foo(): ?int {\n\t}", $generator->generate($method));

		$method = PhpMethod::create('foo')->setType('mixed')->setNullable(true);
		$this->assertEquals("\tpublic function foo(): mixed {\n\t}", $generator->generate($method));
	}

	public function testPhp74ReturnType(): void {
		$generator = new CodeGenerator(['generateDocblock' => false, 'minPhpVersion' => '7.4']);

		$method = PhpMethod::create('foo')->setType('int');
		$this->assertEquals("\tpublic function foo(): int {\n\t}", $generator->generate($method));

		$method = PhpMethod::create('foo')->setType('mixed');
		$this->assertEquals("\tpublic function foo() {\n\t}", $generator->generate($method));

		$method = PhpMethod::create('foo')->setType('Class1|Class2|string|null');
		$this->assertEquals("\tpublic function foo() {\n\t}", $generator->generate($method));
	}
}
