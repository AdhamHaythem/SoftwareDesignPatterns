
<?php
require_once '../Model/instructor.php';
class InstructorController {
    private $view;

    // Method to start lesson
    public function startLesson(): void {
        // Logic to start lesson
    }

    // Method to end lesson
    public function endLesson(): void {
        // Logic to end lesson
    }

}

// InstructorController actions
    $instructorController = new InstructorController();
    if ($_POST['action'] === 'startLesson') {
        $instructorController->startLesson();
    } elseif ($_POST['action'] === 'endLesson') {
        $instructorController->endLesson();
    } elseif ($_POST['action'] === 'enrollLesson') {
        $instructorController->enrollLesson();
    }

    
   
   
?>
