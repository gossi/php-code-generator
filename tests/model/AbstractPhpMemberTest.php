<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\tests\model;

use gossi\codegen\model\AbstractPhpMember;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @group model
 */
class AbstractPhpMemberTest extends TestCase {
	public function testSetGetStatic(): void {
		$member = $this->getMember();

		$this->assertFalse($member->isStatic());
		$this->assertSame($member, $member->setStatic(true));
		$this->assertTrue($member->isStatic());
		$this->assertSame($member, $member->setStatic(false));
		$this->assertFalse($member->isStatic());
	}

	public function testSetGetVisibility(): void {
		$member = $this->getMember();

		$this->assertEquals('public', $member->getVisibility());
		$this->assertSame($member, $member->setVisibility('private'));
		$this->assertEquals('private', $member->getVisibility());
	}

	public function testSetVisibilityThrowsExOnInvalidValue(): void {
		$this->expectException(InvalidArgumentException::class);

		$member = $this->getMember();
		$member->setVisibility('foo');
	}

	public function testSetGetName(): void {
		$member = $this->getMember();

		$this->assertNotNull($member->getName());
		$this->assertSame($member, $member->setName('foo'));
		$this->assertEquals('foo', $member->getName());
	}

	public function testSetGetDocblock(): void {
		$member = $this->getMember();

		$this->assertNotNull($member->getDocblock());
		$this->assertSame($member, $member->setDocblock('foo'));
		$this->assertEquals('foo', $member->getDocblock()->getShortDescription());
	}

	private function getMember(): AbstractPhpMember {
		return $this->getMockForAbstractClass('gossi\codegen\model\AbstractPhpMember', [
			'__not_null__'
		]);
	}
}
