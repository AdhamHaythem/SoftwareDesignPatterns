<?php
require_once '../Model/instructor.php';
require_once '../Model/Lesson.php';

class LessonController 
{
    function createLesson($lessonId, $lessonName, $lessonSubject, $duration, $instructorId)
    {
        // Retrieve the instructor
        $instructor = InstructorModel::retrieve($instructorId);

        // Validate the instructor
        if (!$instructor) {
            die("Error: Instructor with ID $instructorId not found.");
        }

        // Create the lesson and associate it with the instructor
        $lesson = new LessonModel($lessonName, $lessonSubject, $duration, $instructor, $lessonId);
        $instructor->createLesson($lesson);
    }

    function deleteLesson($lessonId, $instructorId)
    {
        // Retrieve the instructor
        $instructor = InstructorModel::retrieve($instructorId);

        // Validate the instructor
        if (!$instructor) {
            die("Error: Instructor with ID $instructorId not found.");
        }

        // Delete the lesson
        $instructor->deleteLesson($lessonId);
    }

    function retrieveLesson($lessonId, $instructorId)
    {
        // Retrieve the instructor
        $instructor = InstructorModel::retrieve($instructorId);

        // Validate the instructor
        if (!$instructor) {
            die("Error: Instructor with ID $instructorId not found.");
        }

        // Retrieve the lesson details
        $lesson = $instructor->retrieveLesson($lessonId);

        // Check if the lesson exists
        if (!$lesson) {
            die("Error: Lesson with ID $lessonId not found.");
        }

        // Display the lesson details
        $lessonView = new StudentView();
        $lessonView->displayLessonDetails($lesson);
    }
}

// POST request handling
$lessonController = new LessonController();

if (isset($_POST['Create'])) {
    if (
        !empty($_POST['lessonId']) &&
        !empty($_POST['lessonName']) &&
        !empty($_POST['lessonSubject']) &&
        !empty($_POST['duration']) &&
        !empty($_POST['instructorId'])
    ) {
        $lessonController->createLesson(
            $_POST['lessonId'],
            $_POST['lessonName'],
            $_POST['lessonSubject'], // Fixed typo here
            $_POST['duration'],
            $_POST['instructorId']
        );
    }
}

if (isset($_POST['delete'])) {
    if (!empty($_POST['lessonId']) && !empty($_POST['instructorId'])) {
        $lessonController->deleteLesson($_POST['lessonId'], $_POST['instructorId']);
    }
}

if (isset($_POST['retrieve'])) {
    if (!empty($_POST['lessonId']) && !empty($_POST['instructorId'])) {
        $lessonController->retrieveLesson($_POST['lessonId'], $_POST['instructorId']);
    }
}
?>
