<?php

abstract class ReportsGenerationTemplate {

    protected array $data = [];
    protected $db ;
    abstract public function getData(String $dataType): array;
    abstract public function generate(array &$object): void;
    abstract public function filterData(int $userID,array $results): array ;
    final public function finalizeReport(array $results , int $userID = 0,): array {
        $result = $this->filterData($userID,$results);
        $this->generate($result);
        return $result;
    }
}
?> 