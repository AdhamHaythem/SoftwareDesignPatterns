<?php
require_once 'employee.php';
require_once 'LessonModel.php';

class InstructorModel extends EmployeeModel {
    private array $lessons = []; // Array to hold LessonModel instances

    public function __construct(
        string $username,
        string $firstname,
        string $lastname,
        int $userID,
        string $email,
        string $usernameID,
        string $password,
        array $location,
        int $phoneNumber,
        string $title,
        int $employeeId,
        int $salary,
        int $workingHours
    ) {
        // Call parent constructor to initialize User properties
        parent::__construct(
            $username,
            $firstname,
            $lastname,
            $userID,
            $email,
            $usernameID,
            $password,
            $location,
            $phoneNumber,
            $title,
            $employeeId,
            $salary,
            $workingHours
        );
    }

    // Add a lesson to the instructor's managed lessons
    public function createLesson(LessonModel $lesson): bool {
        // Add the lesson to the array and return true if successful
        $this->lessons[] = $lesson;
        return true;
    }

    // Retrieve a lesson by its ID
    public function retrieveLesson(int $lessonId): ?LessonModel {
        foreach ($this->lessons as $lesson) {
            if ($lesson->getLessonId() === $lessonId) {
                return $lesson;
            }
        }
        return null; // Return null if no lesson found with the given ID
    }

    // Update an existing lesson
    public function updateLesson(int $lessonId, array $newData): bool {
        foreach ($this->lessons as $lesson) {
            if ($lesson->getLessonId() === $lessonId) {
                // Assuming LessonModel has setters for each property
                if (isset($newData['lessonName'])) {
                    $lesson->setLessonName($newData['lessonName']);
                }
                if (isset($newData['lessonSubject'])) {
                    $lesson->setLessonSubject($newData['lessonSubject']);
                }
                if (isset($newData['duration'])) {
                    $lesson->setDuration($newData['duration']);
                }
                // Update other fields as needed
                return true;
            }
        }
        return false; // Return false if no lesson found with the given ID
    }

    // Delete a lesson by its ID
    public function deleteLesson(int $lessonId): bool {
        foreach ($this->lessons as $index => $lesson) {
            if ($lesson->getLessonId() === $lessonId) {
                unset($this->lessons[$index]); // Remove the lesson from the array
                $this->lessons = array_values($this->lessons); // Re-index array
                return true;
            }
        }
        return false; // Return false if no lesson found with the given ID
    }

    // Method to retrieve all lessons managed by the instructor
    public function getLessons(): array {
        return $this->lessons;
    }
}

?>
