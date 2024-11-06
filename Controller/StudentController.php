<?php
require_once "../Model/userModel.php";
require_once '../Model/instructor.php';
require_once '../Model/student.php';

class StudentController
{
    function enrollLesson($lessonId,$studentId)
    {
        $student = UserModel::retrieve($studentId);
        $lesson = InstructorModel::retrieveLesson($lessonId);
        $student->enrollInLesson($lesson);
    }

    function getlessonList($studentId)
    {
        $studentView = new StudentView();
        $student = UserModel::retrieve($studentId);
        $studentView->displayStudentLessons($student->getEnrolledLessons());

    }

    function completeLesson($studentId,$lessonId)
    {
        $student = UserModel::retrieve($studentId);
        $lesson = InstructorModel::retrieveLesson($lessonId);
        $student->completeLesson($lesson);
    }

    function getLessonProgress($studentId,$lessonId)
    {
        $student = UserModel::retrieve($studentId);
        $lesson = InstructorModel::retrieveLesson($lessonId);
        $student->getLessonProgress($lesson);
    }
}

$studentController = new StudentController();

if(isset($_post['enrolLesson']))
{
    if(!empty($_post['lessonId'])&&!empty($_post['studentId']))
    {
        $studentController->enrollLesson($_post['lessonId'],$_post['studentId']);
    }
}

if(isset($_post['getEnrolledLessons']))
{
    if(!empty($_post['studentId']))
    {
        $studentController->getlessonList($_post['studentId']);
    }
}

if(isset($_post['completeLesson']))
{
    if(!empty($_post['studentId'])&&!empty($_post['lessonId']))
    {
        $studentController->completeLesson($_post['studentId'],$_post['lessonId']);
    }
}

if(isset($_post['lessonProgress']))
{
    if(!empty($_post['studentId'])&&!empty($_post['lessonId']))
    {
        $studentController->getLessonProgress($_post['studentId'],$_post['lessonId']);
    }
}



