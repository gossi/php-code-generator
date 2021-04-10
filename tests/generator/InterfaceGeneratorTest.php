<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\tests\generator;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\generator\CodeGenerator;
use gossi\codegen\model\PhpInterface;
use gossi\codegen\tests\parts\TestUtils;
use PHPUnit\Framework\TestCase;

/**
 * @group generator
 */
class InterfaceGeneratorTest extends TestCase {
	use TestUtils;

	public function testSignature(): void {
		$expected = "interface MyInterface {\n}\n";

		$interface = PhpInterface::create('MyInterface');
		$generator = new CodeGenerator(['generateEmptyDocblock' => false]);
		$code = $generator->generate($interface);

		$this->assertEquals($expected, $code);
	}

	public function testExtends(): void {
		$generator = new CodeGenerator(['generateEmptyDocblock' => false]);

		$expected = "interface MyInterface extends \Iterator {\n}\n";
		$interface = PhpInterface::create('MyInterface')->addInterface('\Iterator');
		$this->assertEquals($expected, $generator->generate($interface));

		$expected = "interface MyInterface extends \Iterator, \ArrayAccess {\n}\n";
		$interface = PhpInterface::create('MyInterface')->addInterface('\Iterator')->addInterface('\ArrayAccess');
		$this->assertEquals($expected, $generator->generate($interface));
	}

	public function testPsr12Interface(): void {
		$interface = PhpInterface::fromFile(__DIR__ . '/../fixtures/psr12/DummyInterface.php');

		$generator = new CodeFileGenerator(['codeStyle' => 'psr-12']);
		$code = $generator->generate($interface);

		$this->assertEquals($this->getFixtureContent('psr12/DummyInterface.php'), $code);
	}
}
