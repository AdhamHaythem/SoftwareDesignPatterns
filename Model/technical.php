<?php
require_once 'employee.php';

class technicalModel extends EmployeeModel {
    private array $skills;
    private array $certifications;

    public function __construct(
        string $username,
        string $firstname,
        string $lastname,
        int $userID,
        string $email,
        string $password,
        array $location,
        int $phoneNumber,
        string $title,
        int $salary,
        int $workingHours,
        array $skills,
        array $certifications
    ) {
        parent::__construct(    
            $username,
            $firstname,
            $lastname,
            $userID,
            $email,
            $password,
            $location,
            $phoneNumber,
            "Technical",
            $salary,
            $workingHours
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
        $sql = "SELECT * FROM technical WHERE userID = :userID";
        $params = [':userID' => $userID];
        
        $dbConnection = UserModel::getDatabaseConnection();
        $result = $dbConnection->query($sql, $params);
        if ($result && !empty($result)) {
            return new technicalModel(
                $result['username'],
                $result['firstname'],
                $result['lastname'],
                $result['userID'],
                $result['email'],
                $result['usernameID'],
                $result['password'],
                json_decode($result['location'], true),
                $result['phoneNumber'],
                $result['title'],
                $result['employeeId'],
                $result['salary'],
                $result['workingHours'],
                json_decode($result['skills'], true),
                json_decode($result['certifications'], true)
            );
        }
        return null;
    }


    public static function update($tech): bool {
    if (!$tech instanceof technicalModel) {
            throw new InvalidArgumentException("Expected instance of tech");
    }
        $sql = "UPDATE technical SET 
                    username = :username, 
                    firstname = :firstname, 
                    lastname = :lastname, 
                    email = :email, 
                    password = :password, 
                    location = :location, 
                    phoneNumber = :phoneNumber,
                    title = :title,
                    salary = :salary,
                    workingHours = :workingHours,
                    skills = :skills,
                    certifications = :certifications
                WHERE userID = :userID";

        $params = [
            $tech->getUsername(),
            $tech->getFirstname(),
            $tech->getLastname(),
            $tech->getEmail(),
            password_hash($tech->getPassword(), PASSWORD_DEFAULT),
            json_encode($tech->getLocation()),
            $tech->getPhoneNumber(),
            $tech->getTitle(),
            $tech->getSalary(),
            $tech->getHoursWorked(),
            json_encode($tech->skills),
            json_encode($tech->certifications),
            $tech->getUserID()
        ];

        $dbConnection = UserModel::getDatabaseConnection();
        return $dbConnection->execute($sql, $params);
    }

    public static function delete($userID): bool {
        $sql = "DELETE FROM technical WHERE userID = :userID";
        $params = [$userID];

        $dbConnection = UserModel::getDatabaseConnection();
        return $dbConnection->execute($sql, $params);
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
