<?php
abstract class MyIterator {
    protected int $position = 0;

    abstract public function rewind(): void;
    abstract public function current(): mixed;
    abstract public function key(): int;
    abstract public function next(): mixed;
    abstract public function valid(): bool;
    abstract public function clear(): void;
}

?>