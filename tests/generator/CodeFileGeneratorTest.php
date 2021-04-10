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
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpConstant;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpParameter;
use gossi\codegen\model\PhpProperty;
use gossi\codegen\tests\Fixtures;
use gossi\codegen\tests\parts\TestUtils;
use PHPUnit\Framework\TestCase;

/**
 * @group generator
 */
class CodeFileGeneratorTest extends TestCase {
	use TestUtils;

	public function testStrictTypesDeclaration(): void {
		$expected = "<?php declare(strict_types=1);\n\nclass MyClass {\n}\n";
		$fn = PhpClass::create('MyClass');

		$codegen = new CodeFileGenerator(['generateDocblock' => false]);
		$code = $codegen->generate($fn);

		$this->assertEquals($expected, $code);
	}

	public function testExpression(): void {
		$class = new PhpClass('ClassWithExpression');
		$class
			->setConstant(PhpConstant::create('FOO', 'BAR'))
			->setProperty(PhpProperty::create('bembel')
				->setExpression("['ebbelwoi' => 'is eh besser', 'als wie' => 'bier']")
			)
			->setMethod(PhpMethod::create('getValue')
				->addParameter(PhpParameter::create('arr')
					->setExpression('[self::FOO => \'baz\']')
			));

		$codegen = new CodeFileGenerator(['generateDocblock' => false, 'declareStrictTypes' => false]);
		$code = $codegen->generate($class);

		$this->assertEquals($this->getGeneratedContent('ClassWithExpression.php'), $code);
	}

	public function testDocblocks(): void {
		$generator = new CodeFileGenerator([
			'headerComment' => 'hui buuh',
			'headerDocblock' => 'woop'
		]);

		$class = new PhpClass('Dummy');
		$code = $generator->generate($class);

		$this->assertEquals($this->getGeneratedContent('Dummy.php'), $code);
	}

	public function testEntity(): void {
		$class = Fixtures::createEntity();

		$generator = new CodeFileGenerator(
			['generateDocblock' => true, 'generateEmptyDocblock' => false]
		);
		$code = $generator->generate($class);

		$this->assertEquals($this->getFixtureContent('Entity.php'), $code);
	}
}
