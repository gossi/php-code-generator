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
use gossi\codegen\model\PhpProperty;
use PHPUnit\Framework\TestCase;

/**
 * @group generator
 */
class PropertyGeneratorTest extends TestCase {
	public function testPublic(): void {
		$expected = "\tpublic \$foo;";

		$prop = PhpProperty::create('foo');
		$generator = new CodeGenerator(['generateEmptyDocblock' => false]);
		$code = $generator->generate($prop);

		$this->assertEquals($expected, $code);
	}

	public function testProtected(): void {
		$expected = "\tprotected \$foo;";

		$prop = PhpProperty::create('foo')->setVisibility(PhpProperty::VISIBILITY_PROTECTED);
		$generator = new CodeGenerator(['generateEmptyDocblock' => false]);
		$code = $generator->generate($prop);

		$this->assertEquals($expected, $code);
	}

	public function testPrivate(): void {
		$expected = "\tprivate \$foo;";

		$prop = PhpProperty::create('foo')->setVisibility(PhpProperty::VISIBILITY_PRIVATE);
		$generator = new CodeGenerator(['generateEmptyDocblock' => false]);
		$code = $generator->generate($prop);

		$this->assertEquals($expected, $code);
	}

	public function testStatic(): void {
		$expected = "\tpublic static \$foo;";

		$prop = PhpProperty::create('foo')->setStatic(true);
		$generator = new CodeGenerator(['generateEmptyDocblock' => false]);
		$code = $generator->generate($prop);

		$this->assertEquals($expected, $code);
	}

	public function testValues(): void {
		$generator = new CodeGenerator(['generateDocblock' => false]);

		$prop = PhpProperty::create('foo')->setValue('string');
		$this->assertEquals("\tpublic \$foo = 'string';", $generator->generate($prop));

		$prop = PhpProperty::create('foo')->setValue(300);
		$this->assertEquals("\tpublic \$foo = 300;", $generator->generate($prop));

		$prop = PhpProperty::create('foo')->setValue(162.5);
		$this->assertEquals("\tpublic \$foo = 162.5;", $generator->generate($prop));

		$prop = PhpProperty::create('foo')->setValue(true);
		$this->assertEquals("\tpublic \$foo = true;", $generator->generate($prop));

		$prop = PhpProperty::create('foo')->setValue(false);
		$this->assertEquals("\tpublic \$foo = false;", $generator->generate($prop));

		$prop = PhpProperty::create('foo')->setValue(null);
		$this->assertEquals("\tpublic \$foo = null;", $generator->generate($prop));

		$prop = PhpProperty::create('foo')->setValue(PhpConstant::create('BAR'));
		$this->assertEquals("\tpublic \$foo = BAR;", $generator->generate($prop));

		$prop = PhpProperty::create('foo')->setExpression("['bar' => 'baz']");
		$this->assertEquals("\tpublic \$foo = ['bar' => 'baz'];", $generator->generate($prop));
	}

	public function testTypes(): void {
		$generator = new CodeGenerator(['generateDocblock' => false]);

		$prop = PhpProperty::create('foo')->setType('string')->setValue('name');
		$this->assertEquals("\tpublic string \$foo = 'name';", $generator->generate($prop));

		$prop = PhpProperty::create('foo')->setType('mixed');
		$this->assertEquals("\tpublic mixed \$foo;", $generator->generate($prop));

		$prop = PhpProperty::create('foo')->setType('string|Text');
		$this->assertEquals("\tpublic string|Text \$foo;", $generator->generate($prop));

		$prop = PhpProperty::create('foo')->setType('string')->setValue(null)->setNullable(true);
		$this->assertEquals("\tpublic ?string \$foo = null;", $generator->generate($prop));

		$prop = PhpProperty::create('foo')->setType('mixed')->setNullable(true);
		$this->assertEquals("\tpublic mixed \$foo;", $generator->generate($prop));
	}

	public function testPhp74Types(): void {
		$generator = new CodeGenerator(['generateDocblock' => false, 'minPhpVersion' => '7.4']);

		$prop = PhpProperty::create('foo')->setType('string')->setValue('string');
		$this->assertEquals("\tpublic string \$foo = 'string';", $generator->generate($prop));

		$prop = PhpProperty::create('foo')->setType('mixed');
		$this->assertEquals("\tpublic \$foo;", $generator->generate($prop));

		$prop = PhpProperty::create('foo')->setType('string|Text');
		$this->assertEquals("\tpublic \$foo;", $generator->generate($prop));
	}
}
