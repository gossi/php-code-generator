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
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpProperty;
use PHPUnit\Framework\TestCase;

/**
 * @group generator
 */
class TemplateTest extends TestCase {
	public function testOverwriteTemplate(): void {
		$expected = '	/**
	 * @return int
	 */
	public function foo(): int
	{ //my own template
		return random_int(1, 99);
	}';

		$generator = new CodeGenerator([
			'templatesDirs' => [__DIR__ . '/../fixtures/templates']
		]);

		$method = PhpMethod::create('foo')->setType('int')->setBody('return random_int(1, 99);');
		$this->assertEquals($expected, $generator->generate($method));
	}

	public function testOverwritePsr12Template(): void {
		$expected = '<?php declare(strict_types=1);

class Foo
{

    public string $bar;
}
';

		$generator = new CodeFileGenerator([
			'templatesDirs' => [__DIR__ . '/../fixtures/templates'],
			'codeStyle' => 'psr-12',
			'generateDocblock' => false
		]);

		$class = PhpClass::create('Foo')->setProperty(PhpProperty::create('bar')->setType('string'));
		$this->assertEquals($expected, $generator->generate($class));
	}
}
