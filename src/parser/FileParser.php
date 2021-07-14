<?php declare(strict_types=1);
/*
 * This file is part of the php-code-generator package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license Apache-2.0
 */

namespace gossi\codegen\parser;

use gossi\codegen\parser\visitor\ParserVisitorInterface;
use phootwork\collection\Set;
use phootwork\file\exception\FileException;
use phootwork\file\File;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Parser;
use PhpParser\ParserFactory;

class FileParser extends NodeVisitorAbstract {
	private Set $visitors;
	private File $file;

	public function __construct(string $filename) {
		$this->file = new File($filename);
		$this->visitors = new Set();
	}

	public function addVisitor(ParserVisitorInterface $visitor): self {
		$this->visitors->add($visitor);

		return $this;
	}

	public function removeVisitor(ParserVisitorInterface $visitor): self {
		$this->visitors->remove($visitor);

		return $this;
	}

	public function hasVisitor(ParserVisitorInterface $visitor): bool {
		return $this->visitors->contains($visitor);
	}

	/**
	 * @throws FileException If not existent or not readable file
	 */
	public function parse(): void {
		$parser = $this->getParser();
		$traverser = new NodeTraverser();
		$traverser->addVisitor($this);
		$traverser->traverse($parser->parse((string) $this->file->read()));
	}

	private function getParser(): Parser {
		$factory = new ParserFactory();

		return $factory->create(ParserFactory::PREFER_PHP7);
	}

	public function enterNode(Node $node): void {
		foreach ($this->visitors as $visitor) {
			switch ($node->getType()) {
				case 'Stmt_Namespace':
					$visitor->visitNamespace($node);
					break;

				case 'Stmt_UseUse':
					$visitor->visitUseStatement($node);
					break;

				case 'Stmt_Class':
					$visitor->visitStruct($node);
					$visitor->visitClass($node);
					break;

				case 'Stmt_Interface':
					$visitor->visitStruct($node);
					$visitor->visitInterface($node);
					break;

				case 'Stmt_Trait':
					$visitor->visitStruct($node);
					$visitor->visitTrait($node);
					break;

				case 'Stmt_TraitUse':
					$visitor->visitTraitUse($node);
					break;

				case 'Stmt_ClassConst':
					$visitor->visitConstants($node);
					break;

				case 'Stmt_Property':
					$visitor->visitProperty($node);
					break;

				case 'Stmt_ClassMethod':
					$visitor->visitMethod($node);
					break;
			}
		}
	}
}
