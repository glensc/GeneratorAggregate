<?php

namespace glen\GeneratorAggregate;

use Generator;
use IteratorAggregate;
use Traversable;

/**
 * Some kind of weird class to flatten Generators and Iterators
 *
 * It's really created to shortcome missing `yield from $generator` for PHP < 7.0
 *
 * @see http://php.net/manual/en/language.generators.syntax.php#control-structures.yield.from
 * @see https://wiki.php.net/rfc/generator-delegation
 */
class GeneratorAggregate implements IteratorAggregate {
	/**
	 * @var Traversable $it
	 */
	private $it;

	/**
	 * @param Traversable $it
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
	 * @param Traversable $it
	 * @return Generator
	 */
	private function flatten($it) {
		foreach ($it as $item) {
			if ($item instanceof Traversable) {
				foreach ($this->flatten($item) as $inner) {
					yield $inner;
				}
			} else {
				yield $item;
			}
		}
	}
}
