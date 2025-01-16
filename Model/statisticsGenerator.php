<?php
require_once 'reportGeneratingTemplate.php';
class statisticsGenerator extends ReportsGenerationTemplate {

    public function __construct() {
        $this->db = DatabaseConnection::getInstance();

    }

    public function getData(String $dataType): array {
        if ($dataType == 'Donations') {
            $sql = "
                SELECT * from donation
            ";
        }
        else if ($dataType == 'Donor') {

        }
        
            // Execute the query using the custom query function
            try {
                $results = $this->db->query($sql); // No need for `fetch_assoc`
                if (!empty($results)) {
                    foreach ($results as $row) {
                        print_r($row); // Output each row of data
                    }
                } else {
                    echo "No $dataType found.";
                }
            } catch (Exception $e) {
                echo "Query error: " . $e->getMessage();
            }
            return $results;
    }

    public function generate(array $result): void {


    }
    public function filterData(int $userID , array $results): array{
        foreach ($results as $result) {
            if ($result['userID'] == $userID) {
            return $result;
            }
        }
        return [];
    }
}
?>