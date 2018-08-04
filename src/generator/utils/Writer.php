<?php

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
namespace gossi\codegen\generator\utils;

/**
 * A writer implementation.
 *
 * This may be used to simplify writing well-formatted code.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class Writer {

	private $content = '';
	private $indentationLevel = 0;
	private $indentation;

	private $options = [
		'indentation_character' => "\t",
		'indentation_size' => 1
	];

	public function __construct($options = []) {
		$this->options = array_merge($this->options, $options);

		$this->indentation = str_repeat($this->options['indentation_character'], 
			$this->options['indentation_size']);
	}

	public function indent() {
		$this->indentationLevel += 1;

		return $this;
	}

	public function outdent() {
		$this->indentationLevel = max($this->indentationLevel - 1, 0);

		return $this;
	}

	/**
	 *
	 * @param string $content        	
	 */
	public function writeln($content = '') {
		$this->write($content . "\n");

		return $this;
	}

	/**
	 *
	 * @param string $content        	
	 */
	 public function write($content) {
 		$tokens = token_get_all('<?php ' . $content);
 		
 		$heredocEndLines = [];
 		
 		foreach ($tokens as $line => $token) {
 		    if (is_array($token)) {
 				if (token_name($token[0]) === 'T_END_HEREDOC') {
 		        	$heredocEndLines[] = $token[2] - 1;
 				}
 		    }
 		}
 		
 		$lines = explode("\n", $content);
 		
 		for ($i = 0, $c = count($lines); $i < $c; $i++) {
 			if (!in_array($i, $heredocEndLines)) {
 				if ($this->indentationLevel > 0
 						&& !empty($lines[$i])
 						&& (empty($this->content) || "\n" === substr($this->content, -1))) {
 					$this->content .= str_repeat($this->indentation, $this->indentationLevel);
 				}
 			}
 			
 			$this->content .= $lines[$i];

 			if ($i + 1 < $c) {
 				$this->content .= "\n";
 			}
 		}

 		return $this;
 	}

	public function rtrim() {
		$addNl = "\n" === substr($this->content, -1);
		$this->content = rtrim($this->content);

		if ($addNl) {
			$this->content .= "\n";
		}

		return $this;
	}

	public function endsWith($search) {
		return substr($this->content, -strlen($search)) === $search;
	}

	public function reset() {
		$this->content = '';
		$this->indentationLevel = 0;

		return $this;
	}

	public function getContent() {
		return $this->content;
	}
}
