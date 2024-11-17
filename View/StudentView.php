<?php

class StudentView{


    public function StudentViewDetails($StdObj)
    { 
        echo '<!DOCTYPE html>
        <html lang="en" dir="ltr">
        <head>
            <meta charset="utf-8">
            <title>Student</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="style.css">
            <link rel="stylesheet" href="style3.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="javascript.js"></script>
        </head>
        <body>
            <nav>
                <label class="logo">Student Dashboard</label>
                <ul>
                    <li><a href="#" onclick="loadHome()">Home</a></li>
                    <li><a href="#" onclick="LessonDetails(1)">Lesson</a></li>
                    <li><a href="#" onclick="EnrolledLessons(1)">Enrolled Lesson</a></li>
                </ul>
            </nav>
            <div class="container">
                <section>
                        <div id="home">
                            <h2>Welcome to the User Information System</h2>
                            <p>Select a menu item to view more details.</p>
                            <img src="assets/donation.jpg" alt="Welcome Image" style="max-width:100%; height:auto;">
                        </div>
                </section>
            </div>

        </body>
        </html>';
    }

    public function displayLessonDetails($lessonId)
    {
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Lesson Details</title>
            <link rel="stylesheet" href="style3.css">
            <link rel="stylesheet" href="style.css">
        </head>
        <body>
            <div class="container">
                <section>
                    <div class="lesson-details">
                        <h2>Lesson Details</h2>
                        <ul>
                            <li><strong>Lesson Name:</strong> ' . $lessonId->lessonName . '</li>
                            <li><strong>Subject:</strong> ' . $lessonId->lessonSubject . '</li>
                            <li><strong>Duration:</strong> ' . $lessonId->duration . ' minutes</li>
                            <li><strong>Instructor:</strong> ' . $lessonId->instructor . '</li>
                            <li><strong>Views:</strong> ' . $lessonId->lessonViews . '</li>
                        </ul>
                        <button onclick="enrollLesson()" class="enroll-button">Enroll</button>
                    </div>
                </section>
            </div>
            
        </body>
        </html>';
        
    }

    public function displayStudentLessons($lessons)
    {
        echo '<div class="container">
            <section>
                <div class="lesson-details">
                    <h2>Lessons</h2>
                    <ul>';
        foreach ($lessons as $lesson) {
            echo '<li>';
            echo '<p><strong>Lesson Name:</strong> ' . $lesson->lessonName . '</P>';
            echo '<p><strong>Description:</strong> ' . $lesson->lessonSubject . '</p>';
            echo '<p><strong>Instructor:</strong> ' . $lesson->instructor. '</p>';
            echo '<p><strong>Duration:</strong> ' . $lesson->duration . ' minutes <p/>';
            echo '<hr>';
            echo '</li>';
        }
        
        echo '</ul>
                </div>
            </section>
        </div>';
    }

}

$studentView = new StudentView();

// Example data

$enrolledLessons = [
    (object)[
        'lessonName' => 'Introduction to PHP',
        'lessonSubject' => 'Learn the basics of PHP programming.',
        'instructor' => 'Mr. Ahmed',
        'duration' => 60,

    ],
    (object)[
        'lessonName' => 'Advanced JavaScript',
        'lessonSubject' => 'Deep dive into modern JavaScript.',
        'instructor' => 'Ms. Fatima',
        'duration' => 60,
    ]
];

$lesson = (object)[
    'lessonId' => 101,
    'lessonName' => 'Introduction to PHP',
    'lessonSubject' => 'Programming',
    'duration' => 60,
    'instructor' => 'Dr. Smith',
    'lessonViews' => 120
];

$studentView->StudentViewDetails(null); 
$studentView->displayLessonDetails($lesson);
$studentView->displayStudentLessons($enrolledLessons); 
?>
