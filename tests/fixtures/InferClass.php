<?php declare(strict_types=1);

namespace gossi\codegen\tests\fixtures;

use phootwork\lang\Text;

class InferClass {
	private string $stringProperty = '';
	private mixed $mixedProperty;
	protected \IteratorAggregate $iterator;
	public string|Text $text;

	public function manipulate(?string $firstParameter, mixed $secondParameter = null): string {
	}

	private function getMixed(int $param = 1): mixed {
	}
}
