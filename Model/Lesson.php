<?php
require_once 'DatabaseConnection.php';
require_once 'InstructorModel.php';

class LessonModel {
    private int $lessonId;
    private string $lessonName;
    private string $lessonSubject;
    private int $duration;
    private InstructorModel $instructor;
    private array $studentView = []; // Array to represent ArrayList<Student>
    private int $views = 0;
    private DatabaseConnection $dbConnection;
    private int $lessonViews = 0;

    public function __construct(
        int $lessonId,
        string $lessonName,
        string $lessonSubject,
        int $duration,
        InstructorModel $instructor,
        DatabaseConnection $dbConnection
    ) {
        $this->lessonId = $lessonId;
        $this->lessonName = $lessonName;
        $this->lessonSubject = $lessonSubject;
        $this->duration = $duration;
        $this->instructor = $instructor;
        $this->dbConnection = $dbConnection;
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

    // Add a student view
    public function addStudentView(StudentModel $student): void {
        $this->studentView[] = $student;
    }
}

?>
