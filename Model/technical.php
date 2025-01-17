<?php
require_once 'employee.php';

class technicalModel extends EmployeeModel {
    private array $skills;
    private array $certifications;

    public function __construct(
        string $username,
        string $firstname,
        string $lastname,
        string $email,
        string $password,
        array $location,
        int $phoneNumber,
        int $salary,
        int $workingHours,
        array $skills,
        array $certifications,
        int $userID=0
    ) {
        parent::__construct(    
            $username,
            $firstname,
            $lastname,
            $email,
            $password,
            $location,
            $phoneNumber,
            "Technical",
            $salary,
            $workingHours,
            $userID
        );

        $this->skills = $skills;
        $this->certifications = $certifications;
    }

    // CRUD Methods

    public static function create($tech): bool {
        if (!$tech instanceof technicalModel) {
            throw new InvalidArgumentException("Expected instance of technicalModel");
        }
    
        $dbConnection = DatabaseConnection::getInstance();
    
        try {
            // 1. Insert into the `user` table
            $userSql = "INSERT INTO user (username, firstname, lastname, userID, email, password, locationList, phoneNumber, isActive)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
            $userParams = [
                $tech->getUsername(),
                $tech->getFirstname(),
                $tech->getLastname(),
                $tech->getUserID(),
                $tech->getEmail(),
                password_hash($tech->getPassword(), PASSWORD_DEFAULT), // Securely hash the password
                json_encode($tech->getLocation()), // Serialize location list as JSON
                $tech->getPhoneNumber(),
                1 // isActive (true)
            ];
    
            if (!$dbConnection->execute($userSql, $userParams)) {
                throw new Exception("Failed to insert into `user` table.");
            }
    
            // 2. Insert into the `employee` table
            $employeeSql = "INSERT INTO employee (userID, title, salary, workingHours)
                            VALUES (?, ?, ?, ?)";
    
            $employeeParams = [
                $tech->getUserID(),
                $tech->getTitle(),
                $tech->getSalary(),
                $tech->getHoursWorked()
            ];
    
            if (!$dbConnection->execute($employeeSql, $employeeParams)) {
                throw new Exception("Failed to insert into `employee` table.");
            }
    
            // 3. Insert into the `technical` table
            $technicalSql = "INSERT INTO technical (userID, skills, certifications)
                             VALUES (?, ?, ?)";
    
            $technicalParams = [
                $tech->getUserID(),
                json_encode($tech->getSkills()), // Serialize skills as JSON
                json_encode($tech->getCertifications()) // Serialize certifications as JSON
            ];
    
            if (!$dbConnection->execute($technicalSql, $technicalParams)) {
                throw new Exception("Failed to insert into `technical` table.");
            }
    
            // If all insertions are successful, return true
            return true;
    
        } catch (Exception $e) {
            // Log the error and return false
            error_log("Error creating technical: " . $e->getMessage());
            return false;
        }
    }
    
    
    public static function retrieve($userID): ?technicalModel {
        $dbConnection = DatabaseConnection::getInstance();
    
        // Query to retrieve technical details joined with user and employee
        $sql = "SELECT * FROM technical t
                JOIN employee e ON t.userID = e.userID
                JOIN user u ON e.userID = u.userID
                WHERE t.userID = ?";
        $params = [$userID];
    
        // Execute the query
        $result = $dbConnection->query($sql, $params);
    
        echo "Query Result:\n";
        print_r($result);
    
        if ($result && !empty($result)) {
            $row = $result[0];
    
            // Validate required fields
            if (
                isset(
                    $row['userID'], $row['username'], $row['firstName'], $row['lastName'], 
                    $row['email'], $row['password'], $row['locationList'], $row['phoneNumber'],
                    $row['title'], $row['salary'], $row['workingHours'], 
                    $row['skills'], $row['certifications']
                )
            ) {
                // Create a new technicalModel instance
                $technical = new technicalModel(
                    $row['username'],                               // username
                    $row['firstName'],                              // firstname
                    $row['lastName'],                               // lastname
                    $row['email'],                                  // email
                    $row['password'],                               // password
                    json_decode($row['locationList'], true),        // location
                    (int)$row['phoneNumber'],                       // phoneNumber
                    (int)$row['salary'],                            // salary
                    (int)$row['workingHours'],                      // workingHours
                    json_decode($row['skills'], true) ?? [],        // skills
                    json_decode($row['certifications'], true) ?? [],// certifications
                    (int)$row['userID']                             // userID
                );
    
                return $technical;
            } else {
                throw new Exception("Missing required fields in the query result.");
            }
        }
    
        // Return null if no result is found
        return null;
    }
    


    public static function update($tech): bool {
        if (!$tech instanceof technicalModel) {
            throw new InvalidArgumentException("Expected instance of technicalModel");
        }
    
        // SQL query to update the technical table and relevant fields
        $sql = "UPDATE technical t
                JOIN employee e ON t.userID = e.userID
                JOIN user u ON e.userID = u.userID
                SET 
                    u.username = ?, 
                    u.firstName = ?, 
                    u.lastName = ?, 
                    u.email = ?, 
                    u.password = ?, 
                    u.locationList = ?, 
                    u.phoneNumber = ?,
                    e.title = ?, 
                    e.salary = ?, 
                    e.workingHours = ?, 
                    t.skills = ?, 
                    t.certifications = ?
                WHERE t.userID = ?";
    
        // Bind parameters
        $params = [
            $tech->getUsername(),                               // username
            $tech->getFirstname(),                              // firstname
            $tech->getLastname(),                               // lastname
            $tech->getEmail(),                                  // email
            password_hash($tech->getPassword(), PASSWORD_DEFAULT), // password (hashed)
            json_encode($tech->getLocation()),                 // location (JSON encoded)
            $tech->getPhoneNumber(),                           // phoneNumber
            $tech->getTitle(),                                 // title
            $tech->getSalary(),                                // salary
            $tech->getHoursWorked(),                           // workingHours
            json_encode($tech->getSkills()),                   // skills (JSON encoded)
            json_encode($tech->getCertifications()),           // certifications (JSON encoded)
            $tech->getUserID()                                 // userID (where condition)
        ];
    
        // Get the database connection
        $dbConnection = DatabaseConnection::getInstance();
    
        // Execute the query
        try {
            return $dbConnection->execute($sql, $params);
        } catch (Exception $e) {
            // Log the error for debugging
            error_log("Error updating technical record: " . $e->getMessage());
            return false;
        }
    }

    
    public static function uploadReport(string $reportName, string $filePath): bool {
        $fileContent = file_get_contents($filePath);
        if ($fileContent === false) {
            throw new Exception("Failed to read the file at $filePath");
        }
    
        $sql = "INSERT INTO technical_reports (reportName, reportFile) 
                VALUES (? , ?)";
        
        $params = [
            $reportName,
            $fileContent
        ];
    
        $dbConnection = UserModel::getDatabaseConnection();
        return $dbConnection->execute($sql, $params);
    }
    
    public function getReports(): array {
        $sql = "SELECT reportId, reportName FROM technical_reports"; // Removed WHERE clause
    
        $dbConnection = UserModel::getDatabaseConnection();
        $reports = $dbConnection->query($sql);
    
        // Check if $reports is an array and return it directly, or return an empty array if null
        return is_array($reports) ? $reports : [];
    }

    public function getSkills() {
        return $this->skills;
    }

    public function getCertifications() {
        return $this->certifications;
    }

    public function setSkills(array $skills) {

        $this->skills = $skills;

    }

    public function setCertifications(array $certifications) {
        $this->certifications = $certifications;
    }
    public function addSkill(string $skill) {
        if (!in_array($skill, $this->skills)) {
            $this->skills[] = $skill;
        }
    }

    public function addCertification(string $certification) {
        if (!in_array($certification, $this->certifications)) {
            $this->certifications[] = $certification;
        }
    }
    
    

    // Method to download a specific report by reportId
    public function downloadReport(int $reportId): ?array {
        $sql = "SELECT reportName, reportFile FROM technical_reports WHERE reportId = :reportId";
        $params = [
            $reportId,
        ];
        
        $dbConnection = UserModel::getDatabaseConnection();
        $result = $dbConnection->query($sql, $params);
        if ($result && !empty($result)) {
            return [
                $result['reportName'],
                $result['reportFile'] // Binary data of the PDF file
            ];
        }

        return null; // Return null if no report is found
    }
}

?>
