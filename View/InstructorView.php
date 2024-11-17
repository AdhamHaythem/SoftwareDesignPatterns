<?php

class InstructorView{


    public function InstructorViewDetails($StdObj)
    { 
        echo '<!DOCTYPE html>
        <html lang="en" dir="ltr">
        <head>
            <meta charset="utf-8">
            <title>Instructor</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="style.css">
            <link rel="stylesheet" href="style3.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="javascript.js"></script>
        </head>
        <body>
            <nav>
                <label class="logo">Instructor Dashboard</label>
                <ul>
                    <li><a href="#" onclick="loadHome()">Home</a></li>
                    <li><a href="#" onclick="LessonsEnstructor(1)">Lessons</a></li>
                    <li><a href="#" onclick="AllLessonsEnstructor(1)">All Lesson</a></li>
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
    public function displayLesson($lesson)
    { 
        echo '<div class="container">
                <section>
                    <div class="lesson-details">
                        <h2>Lesson Details</h2>
                        <ul>
                            <li><strong>Lesson Name:</strong> ' . $lesson->lessonName . '</li>
                            <li><p><strong>Lesson Subject:</strong> ' . $lesson->lessonSubject . '</li>
                            <li><strong>Lesson Duration:</strong> ' . $lesson->duration .  ' minutes</li>
                            <li><strong>Views:</strong> ' . $lesson-> views .  '</li>
                        </ul>
                    </div>
                </section>
            </div>';
    }
    public function displayAllLessons($lessons)
    {
        echo '<div class="container">
            <section>
                <div class="lesson-details">
                    <h2>Lessons</h2>
                    <ul>';
                    
        foreach ($lessons as $lesson) {
            echo '<li>';
            echo '<p><strong>Lesson Name:</strong> ' . $lesson->lessonName . '<p>';
            echo '<p><strong>Lesson Subject:</strong> ' . $lesson->lessonSubject . '</p>';
            echo '<p><strong>Lesson Duration:</strong> ' . $lesson->duration . 'minutes</p>';
            echo '<p><strong>Views:</strong> ' . $lesson->views. '</p>';
            echo '<hr>';
            echo '</p>';
        }
    
        echo '</ul>
                </div>
            </section>
        </div>';
    }
    

}

// Example Usage
$instructorView = new InstructorView();

// Example data for a single lesson
$lessonDetails = (object)[
    'lessonName' => 'Introduction to Python',
    'lessonSubject' => 'Learn the fundamentals of Python programming.',
    'views' => 25,
    'duration' => 60,
];

// Example data
$allLessons = [
    (object)[
        'lessonName' => 'Introduction to Python',
        'lessonSubject' => 'Learn the fundamentals of Python programming.',
        'duration' => 60,
        'views' => 60,
    ],
    (object)[
        'lessonName' => 'Data Science with R',
        'lessonSubject' => 'Explore data science concepts using R.',
        'duration' => 60,
        'views' => 60,
    ]
];

$instructorView->InstructorViewDetails(null); 
$instructorView->displayLesson($lessonDetails); 
$instructorView->displayAllLessons($allLessons); 
?>

?>
