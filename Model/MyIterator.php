<?php
abstract class MyIterator {
    private int $position = 0;
    private $array; 

    public function __construct($array) {
        $this->array = $array;
        $this->position = 0;
    }
    abstract public function rewind();
    abstract public function current();
    abstract public function key();
    abstract public function next();
    abstract public function valid();
}

?>