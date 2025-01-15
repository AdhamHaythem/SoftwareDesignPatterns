<?php
require_once 'C:\xampp\htdocs\Project\SoftwareDesignPatterns\Model\ReportGeneratingTemplate.php';
class ReportGenerator extends ReportsGenerationTemplate {

    public function getData(String $dataType): void {
        if($dataType=='Instructor'){
            $sql = "SELECT * FROM instructor";
            $result = $this->db->query($sql);
            while ($row = $result->fetch_assoc()) {
                print_r($row);
            }
        }
        
    }

    public function generate(): void {
    }

    public function formatData(): void {
    }

    public function filterData(): void{

    }
}
?>