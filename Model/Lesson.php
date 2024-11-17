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
        int $lessonId,
        string $lessonName,
        string $lessonSubject,
        int $duration,
        InstructorModel $instructor,
    ) {
        $this->lessonId = self::$counter;
        $this->lessonName = $lessonName;
        $this->lessonSubject = $lessonSubject;
        $this->duration = $duration;
        $this->instructor = $instructor;
        self::$counter++;
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
}
?>
