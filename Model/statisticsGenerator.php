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

    public function generate(array &$result): void {
         
        $mean = $result['totalDonations'] / $result['numberOfDonations'];
        $result['mean'] = $mean;

    }
    public function filterData(?int $userID = null, array $results): array
    {
        $filteredResults = [];
        $totalDonations = 0;
        $numberOfDonations = 0;
    
        foreach ($results as $result) {
            // Check if filtering by specific userID
            if ($userID === null || $result['userID'] == $userID) {
                $filteredResults[] = $result;
                $totalDonations += $result['amount'];
                $numberOfDonations++;
            }
        }
    
        // Return the aggregated results
        return [
            'donations' => $filteredResults,
            'totalDonations' => $totalDonations,
            'numberOfDonations' => $numberOfDonations,
        ];
    }
    
}
?>