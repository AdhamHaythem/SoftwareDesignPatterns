<?php
abstract class MyIterator {
    private int $position = 0;
    private $array; 

    public function __construct($array) {
        $this->array = $array;
        $this->position = 0;
    }
    abstract public function rewind(): void;
    abstract public function current(): mixed;
    abstract public function key(): int;
    abstract public function next(): mixed;
    abstract public function valid(): bool;
}

?>