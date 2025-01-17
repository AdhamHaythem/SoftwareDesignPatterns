<?php
require_once "../Model/DonorModel.php";
require_once "../Model/AdminModel.php";
require_once "../Model/employee.php";
require_once "../View/DonorView.php";
require_once "../Model/userModel.php";
require_once "../Model/cash.php";
require_once "../View/UserView.php";
require_once "../Model/EmployeeFactory.php";

//  require_once '../emailSetup/config.php';
 
// require 'vendor/autoload.php'; 

class UserController{
    function createDonor($username , $lastname , $firstname , $userId,$email,$password,$location,$phoneNumber)
    {

        $donor = new Donor(
         username: $username,
         firstname:$firstname,
         lastname: $lastname,
         email:$email,
         password:$password,
         location:$location,
         phoneNumber:$phoneNumber,
         paymentMethod: new Cash(),
         userID:$userId
        );
        Donor::create($donor);
        header('Location: ../View/DonorView.php');
    }

    function createAdmin($username,$lastname,$firstname,$userId,$email,$password,$location,$phoneNumber)
    {
        $admin = new Admin($userId,$username , $firstname,$lastname ,$email,$password,$location,$phoneNumber);
        Admin::create($admin);
        header('Location: ../View/AdminView.php');
    }

    function createEmployee($username,$lastname,$firstname,$userId,$email,$password,$location,$phoneNumber,$title,$salary,$workingHours,$vehicleType,$skills,$certifications,$EmployeeType)
    {
        $factory = new EmployeeFactory();
        $employee = $factory->createEmployee($username,$firstname,$lastname,$userId,$email,$password,$location,$phoneNumber,$title,$salary,$workingHours,$vehicleType,$skills,$certifications,$EmployeeType);
        if($EmployeeType=="HR")
        {
            hrModel::create($employee);
        }
        if($EmployeeType=="Delivery")
        {
            DeliveryPersonnel::create($employee);
        }
        if($EmployeeType=="Technical")
        {
            technicalModel::create($employee);
        }
        if($EmployeeType=="HR")
        {
            InstructorModel::create($employee);
        }
        
    }

    function sendLoginMail(Donor $donor){
        $donorEmail= $donor->getEmail();
        $sendgrid = new \SendGrid(SENDGRID_API_KEY);
        $facade= new EmailServiceFacade($sendgrid);
        $facade->sendLoginMail($donorEmail);
}
    function retrieveDonor($donorId)
    {
        $donor = Donor::retrieve($donorId);
        // header('Location: ../View/DonorView.php');
        $view = new DonorView();
        $view->displayDonorProfile($donor);
        
        $this->sendLoginMail($donor);
    }

    function retrieveuser($userId)
    {
        $admin = Admin::retrieve($userId);
        header('Location: ../View/AdminView.php');
        $view = new AdminView();
        $view->displayUsers($admin);
    }

    // function deleteDonor($donorId)
    // {
    //     Donor::delete($donorId);
    // }

    // function deleteAdmin($userId)
    // {
    //     Admin::delete($userId);
    // }

    // function deleteEmployee($userId)
    // {
    //     EmployeeModel::delete($userId);
    // }

    function deleteUser($userId)
    {
        UserModel::delete($userId);
    }

    function getFullName($userId)
    {
        $user = UserModel::retrieve($userId);
        return $user->getFullName();
    }

    function changePassword($userId,$old,$new)
    { 
        $user = UserModel::retrieve($userId);
        $user->changePassword($old,$new);
    }
}

$x = new UserController();

$x->createEmployee("s","s","s",1,"s@gmail.com","122ertgh",(array)['cairo','e','eg'],1233,'t',1000,10,null,null,null,"HR");

if(isset($_POST['displaysignUp']))
{
    require_once "../View/UserView.php";
   $view = new UserView();
   $view->signUp();
}

if(isset($_POST['displayLogin']))
{
    require_once "../View/UserView.php";
   $view = new UserView();
   $view->signIn();
}


if (isset($_POST['createUser'])) {
    if(isset($_POST['Donor'])){

        if (!empty($_POST['userId']) && !empty($_POST['username']) && !empty($_POST['firstname']) && !empty($_POST['lastname'])
        && !empty($_POST['email']) && !empty($_POST['Password']) && !empty($_POST['Location']) && !empty($_POST['phoneNumber'])) 
    {
            $x->createDonor($_POST['username'],$_POST['lastname'],$_POST['firstname'],$_POST['userId'],$_POST['email'],$_POST['password'],$_POST['location'],$_POST['phoneNumber']);
        }
    }
elseif(isset($_POST['Admin']))
{
    if (!empty($_POST['userId']) && !empty($_POST['username']) && !empty($_POST['firstname']) && !empty($_POST['lastname'])
        && !empty($_POST['email']) && !empty($_POST['Password']) && !empty($_POST['Location']) && !empty($_POST['phoneNumber']) 
        ) {
            $x->createAdmin($_POST['username'],$_POST['lastname'],$_POST['firstname'],$_POST['userId'],$_POST['email'],$_POST['password'],$_POST['location'],$_POST['phoneNumber']);

    }
}
elseif(isset($_POST['Employee']))
{
    if(!empty($_POST['userId']) && !empty($_POST['username']) && !empty($_POST['firstname']) && !empty($_POST['lastname'])
    && !empty($_POST['email']) && !empty($_POST['Password']) && !empty($_POST['Location']) && !empty($_POST['phoneNumber'])&& !empty($_POST['title'])
    && !empty($_POST['salary'])&& !empty($_POST['workingHours'])
)
    {
    if(isset($_POST["HR"]))
    {
        $x->createEmployee($_POST['username'],$_POST['lastname'],$_POST['firstname'],$_POST['userId'],$_POST['email'],$_POST['password'],$_POST['location'],$_POST['phoneNumber'],$_POST['title'],$_POST['salary'],$_POST['workingHours'],null,null,null,"HR");
    }

        if(isset($_POST["Delivery"]))
    {
        $x->createEmployee($_POST['username'],$_POST['lastname'],$_POST['firstname'],$_POST['userId'],$_POST['email'],$_POST['password'],$_POST['location'],$_POST['phoneNumber'],$_POST['title'],$_POST['salary'],$_POST['workingHours'],"Car",null,null,"Delivery");
    }

        if(isset($_POST["Technical"]))
    {
        $x->createEmployee($_POST['username'],$_POST['lastname'],$_POST['firstname'],$_POST['userId'],$_POST['email'],$_POST['password'],$_POST['location'],$_POST['phoneNumber'],$_POST['title'],$_POST['salary'],$_POST['workingHours'],null,[1,2,3],[1,2,3],"Technical");
    }

        if(isset($_POST["Instructor"]))
    {
        $x->createEmployee($_POST['username'],$_POST['lastname'],$_POST['firstname'],$_POST['userId'],$_POST['email'],$_POST['password'],$_POST['location'],$_POST['phoneNumber'],$_POST['title'],$_POST['salary'],$_POST['workingHours'],null,null,null,"Instructor");
    }
    }
}
    return 0;
}

if(isset($_POST['retrieveDonor']))
{
    if(!empty($_POST['donorId']))
    {
        $x->retrieveDonor($_POST['donorId']);
    }
}

if(isset($_POST['retrieveAdmin']))
{
    if(!empty($_POST['userId']))
    {
        $x->retrieveuser($_POST['userId']);
    }
}

if(isset($_POST['deleteUser']))
{
    if(!empty($_POST['userId']))
    {
        $x->deleteUser($_POST['donorId']);
    }
}

// if(isset($_POST['deleteDonor']))
// {
//     if(!empty($_POST['donorId']))
//     {
//         $x->deleteDonor($_POST['donorId']);
//     }
// }

// if(isset($_POST['deleteAdmin']))
// {
//     if(!empty($_POST['userId']))
//     {
//         $x->deleteAdmin($_POST['userId']);
//     }
// }

// if(isset($_POST['deleteEmployee']))
// {
//     if(!empty($_POST['userId']))
//     {
//         $x->deleteEmployee($_POST['userId']);
//     }
// }

if(isset($_POST['fullName']))
{
    if(!empty($_POST['userId']))
    {
        $x->getFullName($_POST['userId']);
    }
}

if(isset($_POST['changePassword']))
{
    if(!empty($_POST['userId'])&&!empty($_POST['old'])&&!empty($_POST['new']))
    {
        $x->changePassword($_POST['userId'],$_POST['old'],$_POST['new']);
    }
}


if(isset($_POST['updateAdmin']))
{
    $admin = Admin::retrieve($_POST["userId"]);
    if(!empty($_POST['userId']))
    {
        if(isset($_POST['username']))
        {
            $admin->setUsername($_POST['username']) ;
        }
        if(isset($_POST['email']))
        {
            $admin->setEmail($_POST['email']);
        }

        if(isset($_POST['firstname']))
        {
            $admin->setFirstname($_POST['firstname']) ;
        }
        if(isset($_POST['lastname']))
        {
            $admin->setLastname($_POST['lastname']);
        }
        if(isset($_POST['Location']))
        {
            $admin->setLocation($_POST['Location']);
        }   
        if(isset($_POST['phoneNumber']))
        {
            $admin->setPhoneNumber($_POST['phoneNumber']);
        }

        Admin::update($admin);

    }
}


if(isset($_POST['updateDonor']))
{
    $donor = Donor::retrieve($_POST["userId"]);
    if(!empty($_POST['userId']))
    {
        if(isset($_POST['username']))
        {
            $donor->setUsername($_POST['username']) ;
        }
        if(isset($_POST['email']))
        {
            $donor->setEmail($_POST['email']);
        }

        if(isset($_POST['firstname']))
        {
            $donor->setFirstname($_POST['firstname']) ;
        }
        if(isset($_POST['lastname']))
        {
            $donor->setLastname($_POST['lastname']);
        }
        if(isset($_Location))
        {
            $donor->setLocation($_POST['Location']);
        }   
        if(isset($_POST['phoneNumber']))
        {
            $donor->setPhoneNumber($_POST['phoneNumber']);
        }

        Donor::update($donor);
    }
}


if(isset($_POST['updateEmployee']))
{
    if(isset($_POST['hr'])){
    $hr = hrModel::retrieve($_POST["userId"]);
    if(!empty($_POST['userId']))
    {
        if(isset($_POST['username']))
        {
            $hr->setUsername($_POST['username']) ;
        }
        if(isset($_POST['email']))
        {
            $hr->setEmail($_POST['email']);
        }

        if(isset($_POST['firstname']))
        {
            $hr->setFirstname($_POST['firstname']) ;
        }
        if(isset($_POST['lastname']))
        {
            $hr->setLastname($_POST['lastname']);
        }
        if(isset($_Location))
        {
            $hr->setLocation($_POST['Location']);
        }   
        if(isset($_POST['phoneNumber']))
        {
            $hr->setPhoneNumber($_POST['phoneNumber']);
        }
        hrModel::update($hr);
    }
    }

    if(isset($_POST['technical'])){
        $technical = technicalModel::retrieve($_POST["userId"]);
        if(!empty($_POST['userId']))
        {
            if(isset($_POST['username']))
            {
                $technical->setUsername($_POST['username']) ;
            }
            if(isset($_POST['email']))
            {
                $technical->setEmail($_POST['email']);
            }
    
            if(isset($_POST['firstname']))
            {
                $technical->setFirstname($_POST['firstname']) ;
            }
            if(isset($_POST['lastname']))
            {
                $technical->setLastname($_POST['lastname']);
            }
            if(isset($_Location))
            {
                $technical->setLocation($_POST['Location']);
            }   
            if(isset($_POST['phoneNumber']))
            {
                $technical->setPhoneNumber($_POST['phoneNumber']);
            }
            technicalModel::update($technical);
        }
        }
        
    if(isset($_POST['delivery'])){
        $delivery = DeliveryPersonnel::retrieve($_POST["userId"]);
        if(!empty($_POST['userId']))
        {
            if(isset($_POST['username']))
            {
                $delivery->setUsername($_POST['username']) ;
            }
            if(isset($_POST['email']))
            {
                $delivery->setEmail($_POST['email']);
            }
    
            if(isset($_POST['firstname']))
            {
                $delivery->setFirstname($_POST['firstname']) ;
            }
            if(isset($_POST['lastname']))
            {
                $delivery->setLastname($_POST['lastname']);
            }
            if(isset($_Location))
            {
                $delivery->setLocation($_POST['Location']);
            }   
            if(isset($_POST['phoneNumber']))
            {
                $delivery->setPhoneNumber($_POST['phoneNumber']);
            }
            DeliveryPersonnel::update($delivery);
        }
        }
}