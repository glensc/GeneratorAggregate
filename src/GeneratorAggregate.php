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
	 * @var Generator|ArrayIterator $it
	 */
	private $it;

	/**
	 * @param Generator|ArrayIterator $it
	 */
	public function __construct($it) {
		$this->it = $it;
	}

	public function getIterator() {
		return $this->flatten($this->it);
	}

	/**
	 * @return array
	 */
	public function toArray() {
		return iterator_to_array($this->getIterator());
	}

	/**
	 * Iterate over $it, recurse if element is iterator or generator
	 *
	 * @param Generator|ArrayIterator $it
	 * @return Generator
	 */
	private function flatten($it) {
		foreach ($it as $item) {
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