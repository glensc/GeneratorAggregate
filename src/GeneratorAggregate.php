<?php

namespace Eventum\Delfi;

use ArrayIterator;
use Closure;
use Generator;
use IteratorAggregate;

/**
 * Some kind of weird class to flatten Generators and Iterators
 *
 * It's really created to shortcome missing `yield from $generator` for PHP < 7.0
 */
class GeneratorAggregate implements IteratorAggregate {
	/**
	 * @var Closure to execute to obtain top level Generator|Iterator
	 */
	private $closure;

	public function __construct($class) {
		$this->closure = $class;
	}

	public function getIterator() {
		$closure = $this->closure;

		return $this->flatten($closure());
	}

	/**
	 * @return array
	 */
	public function toArray() {
		return iterator_to_array($this->getIterator());
	}

	private function flatten($generator) {
		foreach ($generator as $item) {
			if ($item instanceof Generator || $item instanceof ArrayIterator) {
				foreach ($this->flatten($item) as $inner) {
					yield $inner;
				}
			} else {
				yield $item;
			}
		}
	}
}