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
class MethodTest extends TestCase {
	public function testParameters(): void {
		$method = new PhpMethod('needsName');

		$this->assertTrue($method->getParameters()->isEmpty());
		$this->assertSame($method, $method->setParameters($params = [
			new PhpParameter('a')
		]));
		$this->assertSame($params, $method->getParameters()->toArray());

		$this->assertSame($method, $method->addParameter($param = new PhpParameter('b')));
		$this->assertSame($param, $method->getParameter('b'));
		$this->assertSame($param, $method->getParameter(1));
		$params[] = $param;
		$this->assertSame($params, $method->getParameters()->toArray());

		$this->assertSame($method, $method->removeParameter(0));
		$this->assertEquals('b', $method->getParameter(0)->getName());

		unset($params[0]);
		$this->assertEquals([
			$param
		], $method->getParameters()->toArray());

		$this->assertSame($method, $method->addParameter($param = new PhpParameter('c')));
		$params[] = $param;
		$params = array_values($params);
		$this->assertSame($params, $method->getParameters()->toArray());

		$this->assertSame($method, $method->replaceParameter(0, $param = new PhpParameter('a')));
		$params[0] = $param;
		$this->assertSame($params, $method->getParameters()->toArray());

		$method->removeParameter($param);
		$method->removeParameter('c');
		$this->assertTrue($method->getParameters()->isEmpty());
	}

	public function testGetNonExistentParameterByName(): void {
		$this->expectException(\InvalidArgumentException::class);

		$method = new PhpMethod('doink');
		$method->getParameter('x');
	}

	public function testGetNonExistentParameterByIndex(): void {
		$this->expectException(\InvalidArgumentException::class);

		$method = new PhpMethod('doink');
		$method->getParameter(5);
	}

	public function testReplaceNonExistentParameterByIndex(): void {
		$this->expectException(\InvalidArgumentException::class);

		$method = new PhpMethod('doink');
		$method->replaceParameter(5, new PhpParameter());
	}

	public function testRemoveNonExistentParameterByIndex(): void {
		$this->expectException(\InvalidArgumentException::class);

		$method = new PhpMethod('doink');
		$method->removeParameter(5);
	}

	public function testBody(): void {
		$method = new PhpMethod('needsName');

		$this->assertSame('', $method->getBody());
		$this->assertSame($method, $method->setBody('foo'));
		$this->assertEquals('foo', $method->getBody());
	}

	public function testReferenceReturned(): void {
		$method = new PhpMethod('needsName');

		$this->assertFalse($method->isReferenceReturned());
		$this->assertSame($method, $method->setReferenceReturned(true));
		$this->assertTrue($method->isReferenceReturned());
		$this->assertSame($method, $method->setReferenceReturned(false));
		$this->assertFalse($method->isReferenceReturned());
	}
}
