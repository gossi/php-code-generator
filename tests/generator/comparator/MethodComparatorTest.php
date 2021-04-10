<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\tests\generator\comparator;

use gossi\codegen\generator\comparator\DefaultMethodComparator;
use gossi\codegen\model\PhpMethod;
use phootwork\lang\Text;
use PHPUnit\Framework\TestCase;

class MethodComparatorTest extends TestCase {
	public function testThrowsExceptionWhenFirstParameterIsNotPhpMethod(): void {
		$this->expectException(\TypeError::class);
		$this->expectExceptionMessage('DefaultMethodComparator::compare method compares PhpMethod objects only');

		$comparator = new DefaultMethodComparator();
		$comparator->compare(new Text('text'), new PhpMethod('method2'));
	}

	public function testThrowsExceptionWhenSecondParameterIsNotPhpMethod(): void {
		$this->expectException(\TypeError::class);
		$this->expectExceptionMessage('DefaultMethodComparator::compare method compares PhpMethod objects only');

		$comparator = new DefaultMethodComparator();
		$comparator->compare(new PhpMethod('method1'), new Text('text'));
	}
}
