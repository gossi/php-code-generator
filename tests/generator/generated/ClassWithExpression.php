<?php

class ClassWithExpression {

	const FOO = 'BAR';

	public $bembel = ['ebbelwoi' => 'is eh besser', 'als wie' => 'bier'];

	public function getValue($arr = [self::FOO => 'baz']) {
	}
}
