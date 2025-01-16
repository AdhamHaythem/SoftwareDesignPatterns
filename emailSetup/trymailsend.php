<?php

require_once 'config.php';

require 'vendor/autoload.php'; 

$email = new \SendGrid\Mail\Mail(); 
$email->setFrom(From);
$email->setSubject("Account Login");
$email->addTo("seifeldinashraf25@gmail.com");
$email->addContent("text/plain", "You Have just loggedin");
$email->addContent(
    "text/html", "<strong>You Have just loggedin</strong>"
);
$sendgrid = new \SendGrid(SENDGRID_API_KEY);
try {
    $response = $sendgrid->send($email);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
} catch (Exception $e) {
    echo 'Caught exception: '. $e->getMessage() ."\n";
}