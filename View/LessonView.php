<?php
class LessonView
{
    public function displayLessonDetails($lessonId)
    {
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Lesson Details</title>
            <link rel="stylesheet" href="style.css">
        </head>
        <body>
            <nav>
                <label class="logo">Lesson Manager</label>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Lessons</a></li>
                    <li><a href="#">Instructors</a></li>
                </ul>
            </nav>
            <div class="container">
                <section>
                    <div class="lesson-details">
                        <h2>Lesson Details</h2>
                        <ul>
                            <li><strong>Lesson ID:</strong> ' . htmlspecialchars($lessonId->lessonId) . '</li>
                            <li><strong>Lesson Name:</strong> ' . htmlspecialchars($lessonId->lessonName) . '</li>
                            <li><strong>Subject:</strong> ' . htmlspecialchars($lessonId->lessonSubject) . '</li>
                            <li><strong>Duration:</strong> ' . htmlspecialchars($lessonId->duration) . ' minutes</li>
                            <li><strong>Instructor:</strong> ' . htmlspecialchars($lessonId->instructor) . '</li>
                            <li><strong>Views:</strong> ' . htmlspecialchars($lessonId->Views) . '</li>
                        </ul>
                        <button onclick="enrollLesson()" class="enroll-button">Enroll</button>
                    </div>
                </section>
            </div>
            <style>
                /* Styling for the enroll button */
                .enroll-button {
                    display: inline-block;
                    padding: 10px 20px;
                    font-size: 16px;
                    color: #fff;
                    background-color: #007bff;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    margin-top: 20px;
                }
                
                .enroll-button:hover {
                    background-color: #0056b3;
                }

                /* Other styling similar to DonationView */
                /* Reset some basic styling */
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }

                /* Set a background color for the body and font settings */
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    background-color: #f4f4f4;
                    color: #333;
                    padding: 20px;
                }

                /* Container for main content */
                .container {
                    max-width: 1200px;
                    margin: 0 auto;
                    padding: 20px;
                }

                /* Style the navigation bar */
                nav {
                    background-color: #333;
                    padding: 10px 0;
                    margin-bottom: 30px;
                }

                nav .logo {
                    color: #fff;
                    font-size: 24px;
                    font-weight: bold;
                    padding-left: 20px;
                }

                nav ul {
                    list-style-type: none;
                    float: right;
                    margin-right: 20px;
                }

                nav ul li {
                    display: inline;
                    margin-left: 20px;
                }

                nav ul li a {
                    color: #fff;
                    text-decoration: none;
                    font-size: 18px;
                }

                nav ul li a:hover {
                    text-decoration: underline;
                }

                /* Style for the lesson details section */
                .lesson-details {
                    background-color: #fff;
                    border-radius: 8px;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                    padding: 30px;
                    margin-bottom: 30px;
                }

                .lesson-details h2 {
                    font-size: 28px;
                    color: #333;
                    margin-bottom: 20px;
                }

                .lesson-details ul {
                    list-style-type: none;
                }

                .lesson-details ul li {
                    font-size: 18px;
                    margin-bottom: 10px;
                }

                .lesson-details ul li strong {
                    color: #555;
                }

                /* Styling for mobile responsiveness */
                @media (max-width: 768px) {
                    .container {
                        padding: 10px;
                    }

                    nav ul {
                        float: none;
                        text-align: center;
                    }

                    nav ul li {
                        display: block;
                        margin-left: 0;
                        margin-top: 10px;
                    }

                    .lesson-details {
                        padding: 20px;
                    }
                }
            </style>
            
            <script>
                function enrollLesson() {
                    alert("Enrolled in Lesson ID: ' . htmlspecialchars($lessonId->lessonId) . '");
                    // Add actual enrollment handling logic here
                }
            </script>;
            
        </body>
        </html>';
        echo "<script>function LessonDetails(itemId) {
            $.ajax({
                url: 'SoftwareDesignPatterns/Controller/DonationController.php',
                type: 'POST',
                data: {
                    lessonId: \'<?php echo $lessonId -> lessonId ?>\',
                    LessonDetails: \'\',
                },
            });
        };</script>";
        
    }
}

$lessonView = new LessonView();
$lesson = (object)[
    'lessonId' => 101,
    'lessonName' => 'Introduction to PHP',
    'lessonSubject' => 'Programming',
    'duration' => 60,
    'instructor' => 'Dr. Smith',
    'Views' => 120
];
$lessonView->displayLessonDetails($lesson);
?>
