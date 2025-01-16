<?php
require_once '../Model/instructor.php';
require_once '../Model/Lesson.php';
require_once '../View/LessonView.php';
class LessonController 
{
    function createLesson($lessonId,$lessonName,$lessonSubject,$duration,$instructorId)
    {
        $instructor = InstructorModel::retrieve($instructorId);
        $lesson = new LessonModel($lessonId,$lessonName,$lessonSubject,$duration,$instructor);
        $instructor->createLesson($lesson);

    }
    function deleteLesson($lessonId,$instructorId)
    {
        $instructor = InstructorModel::retrieve($instructorId);
       $instructor->deleteLesson($lessonId);
    }

    // function retrieveLesson($lessonId,$instructorId)
    // {
    //     $instructor = InstructorModel::retrieve($instructorId);
    //     $lesson = $instructor->retrieveLesson($lessonId);
    //     $lessonView = new LessonView();
    //     $lessonView->displayLessonDetails($lesson);
    // }


}

$lessonController = new LessonController();
if(isset($_POST['Create']))
{
    if(!empty($_POST['lessonId'])&&!empty($_POST['lessonName'])&&!empty($_POST['lessonSubject'])&&!empty($_POST['duration'])&&!empty($_POST['instructorId']))
    {
        $LessonController->createLesson($_POST['lessonId'],$_POST['lessonName'],$_POST['lessomSubject'],$_POST['duration'],$_POST['instructorId']);
    }
}

if(isset($_POST['delete']))
{
    if(!empty($_POST['lessonId']))
    {
    $LessonController->deleteLesson($_POST['lessonId']);
    }
}

if(isset($_POST['retrieve']))
{
    if(!empty($_POST['lessonId']))
    {
        $LessonController->retrieveLesson($_POST['lessonId']);
        
    }
}
