<?php declare(strict_types=1);
/*
 * Copyright 2011 Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace gossi\codegen\utils;

use ReflectionClass;
use ReflectionFunctionAbstract;
use ReflectionMethod;

class ReflectionUtils {

	/**
	 *
	 * @param ReflectionClass $class
	 * @param bool $publicOnly
	 *
	 * @return ReflectionMethod[]
	 */
	public static function getOverrideableMethods(ReflectionClass $class, bool $publicOnly = false): array {
		$filter = ReflectionMethod::IS_PUBLIC;

		if (!$publicOnly) {
			$filter |= ReflectionMethod::IS_PROTECTED;
		}

		return array_filter($class->getMethods($filter), fn ($method) => !$method->isFinal() && !$method->isStatic());
	}

	/**
	 *
	 * @param string $docComment
	 *
	 * @return string
	 */
	public static function getUnindentedDocComment(string $docComment): string {
		$lines = explode("\n", $docComment);
		$docBlock = '';
		for ($i = 0, $c = count($lines); $i < $c; $i++) {
			if (0 === $i) {
				$docBlock = $lines[0] . "\n";
				continue;
			}

			$docBlock .= ' ' . ltrim($lines[$i]);

			if ($i + 1 < $c) {
				$docBlock .= "\n";
			}
		}

		return $docBlock;
	}

	/**
	 *
	 * @param ReflectionFunctionAbstract $function
	 *
	 * @return string
	 */
	public static function getFunctionBody(ReflectionFunctionAbstract $function): string {
		$source = file($function->getFileName());
		$start = $function->getStartLine() - 1;
		$end = $function->getEndLine();
		$body = implode('', array_slice($source, $start, $end - $start));
		$open = strpos($body, '{');
		$close = strrpos($body, '}');

		return trim(substr($body, $open + 1, (strlen($body) - $close) * -1));
	}
}
