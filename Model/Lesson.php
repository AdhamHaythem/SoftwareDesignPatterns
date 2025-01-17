<?php
require_once 'db_connection.php';
require_once 'instructor.php';
require_once 'IObserver.php';

class LessonModel {
    private array $lessonObservers = [];
    private int $lessonId;
    private string $lessonName;
    private string $lessonSubject;
    private int $duration;
    private InstructorModel $instructor;
    private array $studentView = [];
    private int $views = 0;
    private int $lessonViews = 0;
    private string $status;

    private static int $counter =1;

    public function __construct(
        string $lessonName,
        string $lessonSubject,
        int $duration,
        InstructorModel $instructor,
        int $lessonId = 0
    ) {
        $this->lessonId = $lessonId===0 ? LessonModel::useCounter() : $lessonId;
        $this->lessonName = $lessonName;
        $this->lessonSubject = $lessonSubject;
        $this->duration = $duration;
        $this->instructor = $instructor;
    }


    private static function useCounter(): int {
        $ID = self::$counter;
        self::$counter++;
        $db_connection = DatabaseConnection::getInstance();
        $sql = "UPDATE counters SET LessonID = ? where CounterID = 1";
        $params = [self::$counter];
        $db_connection->execute($sql, $params);
        return $ID;
    }

    public static function setCounter(int $counter): void {
        self::$counter = $counter;
    }

    // Getters
    public function getLessonId(): int {
        return $this->lessonId;
    }

    public function getLessonName(): string {
        return $this->lessonName;
    }

    public function getLessonSubject(): string {
        return $this->lessonSubject;
    }

    public function getDuration(): int {
        return $this->duration;
    }

    public function getInstructor(): InstructorModel {
        return $this->instructor;
    }

    public function getStudentView(): array {
        return $this->studentView;
    }

    public function getViews(): int {
        return $this->views;
    }

    public function getInstructorDetails(): InstructorModel {
        return $this->instructor;
    }

    public function setLessonName(string $lessonName): void {
        $this->lessonName = $lessonName;
    }

    public function setLessonSubject(string $lessonSubject): void {
        $this->lessonSubject = $lessonSubject;
    }

    public function setDuration(int $duration): void {
        $this->duration = $duration;
    }

    public function incrementViews(): void {
        $this->lessonViews++;
    }

    public function viewLessonDetails(): string {
        return "Lesson ID: {$this->lessonId}, Name: {$this->lessonName}, Subject: {$this->lessonSubject}";
    }

    public function registerObserver(IObserver $observer): void {
        $this->lessonObservers[] = $observer;
    }

    public function removeObserver(IObserver $observer): void {
        $index = array_search($observer, $this->lessonObservers, true);
        if ($index !== false) {
            unset($this->lessonObservers[$index]);
        }
    }

    public function notifyObservers(): void {
        foreach ($this->lessonObservers as $observer) {
            $observer->UpdateStatus($this->status);
        }
    }

    public function SetStatus(string $newStatus): void {
        $this->status = $newStatus;
        $this->notifyObservers();
    }

    public function addStudentView(StudentModel $student): void {
        $this->studentView[] = $student;
    }
        // Your LessonModel properties and constructor go here
    
        // CREATE
        public static function create(LessonModel $lesson): bool {
            $dbConnection = DatabaseConnection::getInstance();
    
            $sql = "INSERT INTO lesson (lessonID, lessonName, lessonSubject, duration, instructorID, views)
                    VALUES (?,?, ?, ?, ?, ?)";
            
            $params = [
                $lesson->lessonId,
                $lesson->lessonName,
                $lesson->lessonSubject,
                $lesson->duration,
                $lesson->instructor->getUserID(), // Only store the instructor ID
                $lesson->views
            ];
    
            try {
                return $dbConnection->execute($sql, $params);
            } catch (Exception $e) {
                error_log("Error creating lesson: " . $e->getMessage());
                return false;
            }
        }
    
        // RETRIEVE
        public static function retrieve(int $lessonID): ?LessonModel {
            $dbConnection = DatabaseConnection::getInstance();
    
            $sql = "SELECT * FROM lesson WHERE lessonID = ?";
            $params = [$lessonID];
            $result = $dbConnection->query($sql, $params);
    
            if ($result && !empty($result)) {
                $row = $result[0];
    
                // Use the InstructorModel::retrieve method to get the instructor details
                $instructor = InstructorModel::retrieve($row['instructorID']);
    
                if (!$instructor) {
                    throw new Exception("Instructor not found for lesson.");
                }
    
                return new LessonModel(
                    $row['lessonName'],
                    $row['lessonSubject'],
                    (int) $row['duration'],
                    $instructor,
                    (int) $row['lessonID']
                );
            }
    
            return null;
        }
    
        // UPDATE
        public static function update(LessonModel $lesson): bool {
            $dbConnection = DatabaseConnection::getInstance();
    
            $sql = "UPDATE lesson SET 
                        lessonName = ?, 
                        lessonSubject = ?, 
                        duration = ?, 
                        instructorID = ?, 
                        views = ?
                    WHERE lessonID = ?";
    
            $params = [
                $lesson->lessonName,
                $lesson->lessonSubject,
                $lesson->duration,
                $lesson->instructor->getUserID(), // Only update the instructor ID
                $lesson->views,
                $lesson->lessonId
            ];
    
            try {
                return $dbConnection->execute($sql, $params);
            } catch (Exception $e) {
                error_log("Error updating lesson: " . $e->getMessage());
                return false;
            }
        }
    
        // DELETE
        public static function delete(int $lessonID): bool {
            $dbConnection = DatabaseConnection::getInstance();
    
            $sql = "DELETE FROM lesson WHERE lessonID = ?";
            $params = [$lessonID];
    
            try {
                return $dbConnection->execute($sql, $params);
            } catch (Exception $e) {
                error_log("Error deleting lesson: " . $e->getMessage());
                return false;
            }
        }
}
?>
