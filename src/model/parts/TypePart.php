<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\model\parts;

use phootwork\lang\Text;

/**
 * Type part
 *
 * For all models that have a type
 *
 * @author Thomas Gossmmann
 */
trait TypePart {

	/** @var string */
	private string $type = '';

	/** @var string */
	private string $typeDescription = '';

	/** @var bool */
	private bool $typeNullable = false;

	private array $typeHintMap = [
		'string' => 'string',
		'int' => 'int',
		'integer' => 'int',
		'bool' => 'bool',
		'boolean' => 'bool',
		'float' => 'float',
		'double' => 'float',
		'object' => 'object',
		'mixed' => 'mixed',
		'resource' => 'resource',
		'callable' => 'callable',
		'null' => 'null'
];

	/**
	 * Sets the type
	 * 
	 * @param string $type
	 * @param string $description
	 *
	 * @return $this
	 */
	public function setType(string $type, string $description = ''): self {
		$this->type = Text::create($type)
			->replace(['boolean', 'integer', 'double'], ['bool', 'int', 'float'])
			->toString();

		$this->setTypeDescription($description);

		return $this;
	}

	/**
	 * Sets the description for the type
	 *
	 * @param string $description
	 *
	 * @return $this
	 */
	public function setTypeDescription(string $description): self {
		$this->typeDescription = $description;

		return $this;
	}

	/**
	 * Returns the type
	 *
	 * @return string
	 */
	public function getType(): string {
		return $this->type;
	}

	/**
	 * Returns the type description
	 *
	 * @return string
	 */
	public function getTypeDescription(): string {
		return $this->typeDescription;
	}

	/**
	 * Returns whether the type is nullable
	 * 
	 * @return bool
	 */
	public function getNullable(): bool {
		return $this->typeNullable;
	}

	/**
	 * Sets the type nullable
	 * 
	 * @param bool $nullable
	 *
	 * @return $this
	 */
	public function setNullable(bool $nullable): self {
		$this->typeNullable = $this->type === 'mixed' ? false : $nullable;

		return $this;
	}
}
