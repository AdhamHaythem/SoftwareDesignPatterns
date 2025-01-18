<?php
require_once "UserController.php";
require_once "DonorController.php";
require_once "AdminController.php";
require_once "StudentController.php";
require_once "LessonController.php";
require_once "EventController.php";
require_once "../Model/startup.php";


StartUp::resetDatabase();
StartUp::initialize();
$userController = new UserController();
$donorController = new DonorController();
$adminController = new AdminController();
$eventController = new EventController();
$lessonController = new LessonController();
$studentController = new StudentController();


$donor = new Donor('b','b','b',"b@gmail.com","12345678",['cairo','eg'],12345,null,null,500);
$userController->createEmployee("s","s","s",1,"s@gmail.com","122ertgh",(array)['cairo','e','eg'],1233,'t',1000,10,null,null,null,"HR");
$userController->createEmployee('a','a','a',2,'a@gmail.com',"1234567",['cairo','eg'],12345,'a',1000,24,null,['a','a','a'],['a','a','a'],"Technical");
$userController->createDonor('b','b','b',0,"b@gmail.com","12345678",['cairo','eg'],12345);
$userController->createAdmin('c','c','c',0,'c@gmail.com',"1234567",['cairo','eg'],123456);
$userController->createEmployee('d','d','d',5,'d@gmail.com',"123456789",['cairo','eg'],1234567,'i',100,10,null,null,null,"Instructor");
$lessonController->createLesson(1,'m','m',10,5);
$student = new StudentModel('e','e','e','e@gmial.com','123456789',['cairo','eg'],1234567);
StudentModel::create($student);
echo $donor->getPaymentMethod();
$donorController->setPaymentMethod(new Visa(),3);
echo $donor->getPaymentMethod();
$currentDateTime = new DateTime('now');
$cashDonation =new RegularDonation(200,3,$currentDateTime);
$donation= new CashDonation(100,3,$cashDonation);
$review = new UnderReviewState();
$donation->setState($review);
Donation::create($donation);
$adminController->changeState($donation->getDonationID());
$donorController->undoDonation(3,2);
$donorController->redoDonation(3,2);
$studentController->enrollLesson(1,6,5);


$object = new CampaignStrategy($currentDateTime,['cairo','eg'],10,1,"z",1000,'z','z',0);
$eventController->createCampaign($object);




?>