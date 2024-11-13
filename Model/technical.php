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
            $title,
            $salary,
            $workingHours
        );

        $this->skills = $skills;
        $this->certifications = $certifications;
    }

    // CRUD Methods

    // Create a new technical employee
    public static function create( $tech): bool {
    if (!$tech instanceof technicalModel) {
            throw new InvalidArgumentException("Expected instance of tech");
    }
        $sql = "INSERT INTO technical (username, firstname, lastname, userID, email, usernameID, password, location, phoneNumber, title, employeeId, salary, workingHours, skills, certifications)
                VALUES (:username, :firstname, :lastname, :userID, :email, :usernameID, :password, :location, :phoneNumber, :title, :employeeId, :salary, :workingHours, :skills, :certifications)";
        
        $params = [
            ':username' => $tech->getUsername(),
            ':firstname' => $tech->getFirstname(),
            ':lastname' => $tech->getLastname(),
            ':userID' => $tech->getUserID(),
            ':email' => $tech->getEmail(),
            ':password' => password_hash($tech->getPassword(), PASSWORD_DEFAULT),
            ':location' => json_encode($tech->getLocation()), // Assuming location is an array
            ':phoneNumber' => $tech->getPhoneNumber(),
            ':title' => $tech->getTitle(),
            ':salary' => $tech->getSalary(),
            ':workingHours' => $tech->getHoursWorked(),
            ':skills' => json_encode($tech->skills),
            ':certifications' => json_encode($tech->certifications)
        ];

        $dbConnection = UserModel::getDatabaseConnection();
        return $dbConnection->execute($sql, $params);
    }

    // Retrieve a technical employee by userID
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

    // Update a technical employee
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
            ':username' => $tech->getUsername(),
            ':firstname' => $tech->getFirstname(),
            ':lastname' => $tech->getLastname(),
            ':email' => $tech->getEmail(),
            ':password' => password_hash($tech->getPassword(), PASSWORD_DEFAULT),
            ':location' => json_encode($tech->getLocation()),
            ':phoneNumber' => $tech->getPhoneNumber(),
            ':title' => $tech->getTitle(),
            ':salary' => $tech->getSalary(),
            ':workingHours' => $tech->getHoursWorked(),
            ':skills' => json_encode($tech->skills),
            ':certifications' => json_encode($tech->certifications),
            ':userID' => $tech->getUserID()
        ];

        $dbConnection = UserModel::getDatabaseConnection();
        return $dbConnection->execute($sql, $params);
    }

    // Delete a technical employee by userID
    public static function delete($userID): bool {
        $sql = "DELETE FROM technical WHERE userID = :userID";
        $params = [':userID' => $userID];

        $dbConnection = UserModel::getDatabaseConnection();
        return $dbConnection->execute($sql, $params);
    }

    // Method to upload a PDF report file
    public static function uploadReport(string $reportName, string $filePath): bool {
        // Read the PDF file as binary
        $fileContent = file_get_contents($filePath);
        if ($fileContent === false) {
            throw new Exception("Failed to read the file at $filePath");
        }
    
        $sql = "INSERT INTO technical_reports (reportName, reportFile) 
                VALUES (:reportName, :reportFile)";
        
        $params = [
            ':reportName' => $reportName,
            ':reportFile' => $fileContent // Store the binary content directly
        ];
    
        $dbConnection = UserModel::getDatabaseConnection();
        return $dbConnection->execute($sql, $params);
    }
    
    // Method to retrieve all reports for this technical employee
    public function getReports(): array {
        $sql = "SELECT reportId, reportName FROM technical_reports"; // Removed WHERE clause
    
        $dbConnection = UserModel::getDatabaseConnection();
        $reports = $dbConnection->query($sql);
    
        // Check if $reports is an array and return it directly, or return an empty array if null
        return is_array($reports) ? $reports : [];
    }
    
    

    // Method to download a specific report by reportId
    public function downloadReport(int $reportId): ?array {
        $sql = "SELECT reportName, reportFile FROM technical_reports WHERE reportId = :reportId";
        $params = [
            ':reportId' => $reportId,
        ];
        
        $dbConnection = UserModel::getDatabaseConnection();
        $result = $dbConnection->query($sql, $params);
        if ($result && !empty($result)) {
            return [
                'reportName' => $result['reportName'],
                'reportFile' => $result['reportFile'] // Binary data of the PDF file
            ];
        }

        return null; // Return null if no report is found
    }
}

?>
