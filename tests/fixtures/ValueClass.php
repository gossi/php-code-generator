<?php declare(strict_types=1);
namespace gossi\codegen\tests\fixtures;

class ValueClass {
	const CONST_STRING = 'foo';
	const CONST_INTEGER = 10;
	const CONST_FLOAT = 7.5;
	const CONST_BOOL = true;
	const CONST_NULL = null;
	const CONST_CONST = self::CONST_STRING;

	private $propString = 'foo';
	private $propInteger = 10;
	private $propFloat = 7.5;
	private $propBool = true;
	private $propNull = null;
	private $propConst = self::CONST_STRING;
	private $propExpr = ['foo' => ['bar' => 'baz']];

	public function values($none, $paramString = 'foo', $paramInteger = 10, $paramFloat = 7.5, $paramBool = true, $paramNull = null, $paramConst = self::CONST_STRING, $paramExpr = ['foo' => ['bar' => 'baz']]) {
	}
}
