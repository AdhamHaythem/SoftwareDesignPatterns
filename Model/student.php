<?php
require_once 'UserModel.php';
require_once 'LessonModel.php';

class StudentModel extends UserModel { 
    private array $enrolledLessons = []; // Array to hold enrolled LessonModel instances
    private array $lessonCompletionStatus = []; // Array to track completion status by lesson ID

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
        DatabaseConnection $dbConnection
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
            $dbConnection
        );
    }

    // Enroll in a lesson
    public function enrollInLesson(LessonModel $lesson): void {
        $this->enrolledLessons[] = $lesson;
        $this->lessonCompletionStatus[$lesson->getLessonId()] = false; // Mark as not completed by default
    }

    // Complete a lesson for this student
    public function completeLesson(LessonModel $lesson): void {
        $lessonId = $lesson->getLessonId();
        if (isset($this->lessonCompletionStatus[$lessonId])) {
            $this->lessonCompletionStatus[$lessonId] = true; // Mark the lesson as completed
        } else {
            // Handle case where the lesson was not enrolled (optional)
            throw new Exception("Lesson not found in enrolled lessons.");
        }
    }

    // Check if a lesson is completed for this student
    public function isLessonCompleted(LessonModel $lesson): bool {
        $lessonId = $lesson->getLessonId();
        return $this->lessonCompletionStatus[$lessonId] ?? false;
    }

    // Get all completed lessons
    public function getCompletedLessons(): array {
        $completedLessons = [];
        foreach ($this->enrolledLessons as $lesson) {
            if ($this->isLessonCompleted($lesson)) {
                $completedLessons[] = $lesson;
            }
        }
        return $completedLessons;
    }

    // Increment views for a specific lesson
    public function incrementLessonViews(LessonModel $lesson): void {
        $lesson->incrementViews(); // Assuming LessonModel has a method to increment views
    }

    // Retrieve all enrolled lessons
    public function getEnrolledLessons(): array {
        return $this->enrolledLessons;
    }

    // Get progress of a specific lesson
    public function getLessonProgress(LessonModel $lesson): string {
        if ($this->isLessonCompleted($lesson)) {
            return "Completed";
        }
        return "In Progress";
    }

    // Update method for observer pattern
    public function update(): void {
        // Code to handle updates, typically used in observer pattern
    }
}

?>
