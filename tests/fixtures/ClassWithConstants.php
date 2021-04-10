<?php declare(strict_types=1);
namespace gossi\codegen\tests\fixtures;

class ClassWithConstants {
	const BAR = self::FOO;

	const FOO = 'bar';

	const NMBR = 300;
}
