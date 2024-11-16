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

    function retrieveLesson($lessonId,$instructorId)
    {
        $instructor = InstructorModel::retrieve($instructorId);
        $lesson = $instructor->retrieveLesson($lessonId);
        $lessonView = new LessonView();
        $lessonView->displayLessonDetails($lesson);
    }


}

$lessonController = new LessonController();
if(isset($_post['Create']))
{
    if(!empty($_post['lessonId'])&&!empty($_post['lessonName'])&&!empty($_post['lessonSubject'])&&!empty($_post['duration'])&&!empty($_post['instructorId']))
    {
        $LessonController->createLesson($_post['lessonId'],$_post['lessonName'],$_post['lessomSubject'],$_post['duration'],$_post['instructorId']);
    }
}

if(isset($_post['delete']))
{
    if(!empty($_post['lessonId']))
    {
    $LessonController->deleteLesson($_post['lessonId']);
    }
}

if(isset($_post['retrieve']))
{
    if(!empty($_post['lessonId']))
    {
        $LessonController->retrieveLesson($_post['lessonId']);
        
    }
}
