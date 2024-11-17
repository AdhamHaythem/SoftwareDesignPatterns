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
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
        </head>
        <body>

            <div id="content">
                <h2>Welcome to the Instructor View</h2>
            </div>

        </body>
        </html>';
    }
    public function displayLesson($StdObj)
    { 
        echo '<div class="lesson-display">';
        echo 'A lesson will be displayed here ya Doniaaaaaa3333';
        echo '</div>';
    }


    public function displayAllLessons()
    {
        echo '<div class="lessons-list">';
        echo 'Lessons will be displayed here ya Doniaaaaaa3333';
        echo '</div>';
    }

}

// Usage example
//$eventView = new InstructorView();

?>
