<?php

abstract class ReportsGenerationTemplate {

    protected array $data = [];
    protected $db ;
    abstract public function getData(String $dataType): array;
    abstract public function generate(Object $object): void;
    abstract public function filterData(int $userID,array $results): array ;
    abstract public function formatData(array $result): Object;
    final public function finalizeReport(int $userID,array $results): Object {
        $result = $this->filterData($userID,$results);
        $object = $this->formatData($result);
        return $object;
    }
}
?> 