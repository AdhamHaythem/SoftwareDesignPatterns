<?php

abstract class ReportsGenerationTemplate {

    protected array $data = [];
    protected $db =DatabaseConnection::getInstance();
    abstract public function getData(String $dataType): void;
    abstract public function generate(): void;

    

    abstract public function formatData(): void;
    abstract public function filterData(): void ;
    final public function finalizeReport(String $dataType): void {
        $this->getData($dataType);
        $this->formatData();
        $this->filterData();
        $this->generate();
    }
}
?>