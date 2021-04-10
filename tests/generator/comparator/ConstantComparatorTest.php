<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\tests\generator\comparator;

use gossi\codegen\generator\comparator\DefaultConstantComparator;
use gossi\codegen\model\PhpConstant;
use phootwork\lang\Text;
use PHPUnit\Framework\TestCase;

class ConstantComparatorTest extends TestCase {
	public function testThrowsExceptionWhenFirstParameterIsNotPhpConstant(): void {
		$this->expectException(\TypeError::class);
		$this->expectExceptionMessage('DefaultConstantComparator::compare method compares PhpConstant objects only');

		$comparator = new DefaultConstantComparator();
		$comparator->compare(new Text('text'), new PhpConstant('constant2'));
	}

	public function testThrowsExceptionWhenSecondParameterIsNotPhpConstant(): void {
		$this->expectException(\TypeError::class);
		$this->expectExceptionMessage('DefaultConstantComparator::compare method compares PhpConstant objects only');

		$comparator = new DefaultConstantComparator();
		$comparator->compare(new PhpConstant('constant1'), new Text('text'));
	}
}
