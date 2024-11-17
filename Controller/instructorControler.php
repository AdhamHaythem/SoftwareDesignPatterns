<?php
require_once '../Model/instructor.php';
require_once '../Model/LessonController.php';
require_once '../View/InstructorView.php';
class InstructorController {

    public function getLessons($instructorId): void {
        $instructor= InstructorModel::retrieve($instructorId);
        $instructor->getLessons();
        $view= new InstructorView();
        $view->displayAllLessons();
    }

    public function retrieveLesson($lessonId,$instructorId): void {
        $instructor= InstructorModel::retrieve($instructorId);
        $instructor->retrieveLesson($lessonId);
        $view= new InstructorView();
        $view->displayLesson($lessonId);
    }

}

    $instructorController = new InstructorController();

    if(isset($_POST['retrieveLesson']))
{
    if(!empty($_POST['lessonId']) &&!empty($_POST['instructorId']))
    {
        $instructorController->retrieveLesson($_POST['lessonId'],$_POST['instructorId']);
    }
}

if(isset($_POST['getLessons'])){

    if(!empty($_POST['instructorId'])){
    $instructorController->getLessons($_POST['instructorId']);
    }
}
    
   
   
?>
