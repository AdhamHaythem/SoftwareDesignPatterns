<?php
//Delete
class InstructorController {
    private $view;

    // Constructor to initialize InstructorView
    public function __construct() {
        $this->view = new InstructorView();
    }

    // Method to start lesson
    public function startLesson(): void {
        // Logic to start lesson
    }

    // Method to end lesson
    public function endLesson(): void {
        // Logic to end lesson
    }

    // Method to enroll in lesson
    public function enrollLesson(): bool {
        // Logic to enroll in a lesson
        return true; // Return success or failure based on logic
    }
}


    
   
   
?>