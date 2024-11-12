<?php

interface IMaintainable {
    public static function create($object): bool;  //passing object with info we want to save, it can be instance of class as well
    public static function retrieve($key);   //passing key is identifier for each entry stored
    public static function update($key): bool;
    public static function delete($key): bool;
}

?>
