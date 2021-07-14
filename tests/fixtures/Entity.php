<?php declare(strict_types=1);

namespace gossi\codegen\tests\fixtures;

/**
 * Doc Comment.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
abstract class Entity {

	private $enabled = false;

	/**
	 * @var int
	 */
	private int $id;

	private static function bar() {
	}

	/**
	 * Another doc comment.
	 *
	 * @param $a
	 * @param array $b
	 * @param \stdClass $c
	 * @param string $d
	 * @param callable $e
	 */
	final public function __construct($a, array &$b, \stdClass $c, string $d = 'foo', callable $e) {
	}

	abstract protected function foo();
}
