<?php
 require_once '../emailSetup/config.php';
class EmailServiceFacade {
    private $sendgrid;

    public function __construct($apiKey) {
        $this->sendgrid = new \SendGrid($apiKey);
    }

    public function sendLoginMail(String $donorEmail) {
        $email = new \SendGrid\Mail\Mail(); 
        $email->setFrom(From); // Fetch from env variables
        $email->setSubject("Account Login");
        $email->addTo($donorEmail);
        $email->addContent("text/plain", "You Have just logged in");
        $email->addContent("text/html", "<strong>You Have just logged in to your account</strong>");

        try {
            $response = $this->sendgrid->send($email);
            return $response->statusCode(); // Return status for logging or confirmation
        } catch (Exception $e) {
            // Handle the exception, e.g., log it
            error_log('Email sending failed: ' . $e->getMessage());
            return false;
        }
    }
}
?>