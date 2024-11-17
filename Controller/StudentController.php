<?php
require_once "../Model/userModel.php";
require_once '../Model/instructor.php';
require_once '../Model/student.php';

class StudentController
{
    function enrollLesson($lessonId,$studentId,$instructorId)
    {
        $instructor= InstructorModel::retrieve($instructorId);
        $student = StudentModel::retrieve($studentId);
        $lesson = $instructor->retrieveLesson($lessonId);
        $student->enrollInLesson($lesson);
    }

    function getlessonList($studentId)
    {
        $studentView = new StudentView();
        $student = StudentModel::retrieve($studentId);
        $studentView->displayStudentLessons($student->getEnrolledLessons());
    }

    function completeLesson($studentId,$lessonId,$instructorId)
    {        
        $instructor= InstructorModel::retrieve($instructorId);
        $student = StudentModel::retrieve($studentId);
        $lesson = $instructor->retrieveLesson($lessonId);
        $student->completeLesson($lesson);
    }

    function getLessonProgress($studentId,$lessonId,$instructorId)
    {
        $student = StudentModel::retrieve($studentId);
        $instructor= InstructorModel::retrieve($instructorId);
        $lesson = $instructor->retrieveLesson($lessonId);
        $student->getLessonProgress($lesson);
    }
}

$studentController = new StudentController();

if(isset($_POST['enrollLesson']))
{
    if(!empty($_POST['lessonId'])&&!empty($_POST['studentId']))
    {
        $studentController->enrollLesson($_POST['lessonId'],$_POST['studentId'],$_POST['instructorId']);
    }
}

if(isset($_POST['getEnrolledLessons']))
{
    if(!empty($_POST['studentId']))
    {
        $studentController->getlessonList($_POST['studentId']);
    }
}

if(isset($_POST['completeLesson']))
{
    if(!empty($_POST['studentId'])&&!empty($_POST['lessonId']))
    {
        $studentController->completeLesson($_POST['studentId'],$_POST['lessonId'],$_POST['instructorId']);
    }
}

if(isset($_POST['getLessonProgress']))
{
    if(!empty($_POST['studentId'])&&!empty($_POST['lessonId']))
    {
        $studentController->getLessonProgress($_POST['studentId'],$_POST['lessonId'],$_POST['instructorId']);
    }
}



