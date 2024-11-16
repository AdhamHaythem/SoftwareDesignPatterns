<?php
require_once "../Model/DonorModel.php";
require_once "../Model/AdminModel.php";
require_once "../Model/EmployeeModel.php";
require_once "../Model/DonorView.php";
require_once "../Model/userModel.php";
require_once "../Model/cash.php";

class UserController{
    function createDonor($username , $lastname , $firstname , $userId,$email,$password,$location,$phoneNumber,$event)
    {

        $donor = new Donor($userId,$username , $firstname,$lastname  ,$email,$password,$location,$phoneNumber,new Cash(),new VolunteeringEventStrategy(),$event);
        Donor::create($donor);
    }

    function createAdmin($username,$lastname,$firstname,$userId,$email,$password,$location,$phoneNumber)
    {
        $admin = new Admin($userId,$username , $firstname,$lastname ,$email,$password,$location,$phoneNumber);
        Admin::create($admin);
    }

    function createEmployee($username,$lastname,$firstname,$userId,$email,$password,$location,$phoneNumber,$title,$salary,$workingHours)
    {
        $employee = new EmployeeModel($username,$firstname,$lastname,$userId,$email,$password,$location,$phoneNumber,$title,$salary,$workingHours);
        EmployeeModel::create($employee);
    }

    function retrieveDonor($donorId)
    {
        $donor = Donor::retrieve($donorId);
        $view = new DonorView();
        $view->displayDonorProfile($donor);

    }

    function retrieveuser($userId)
    {
        $admin = Admin::retrieve($userId);
        $view = new AdminView();
        $view->displayUsers($admin);
    }

    function deleteDonor($donorId)
    {
        Donor::delete($donorId);
    }

    function deleteAdmin($userId)
    {
        Admin::delete($userId);
    }

    function deleteEmployee($userId)
    {
        EmployeeModel::delete($userId);
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
if (isset($_POST['createUser'])) {
    if(isset($_POST['Donor'])){

        if (!empty($_POST['userId']) && !empty($_POST['username']) && !empty($_POST['firstname']) && !empty($_POST['lastname'])
        && !empty($_POST['email']) && !empty($_POST['Password']) && !empty($_POST['Location']) && !empty($_POST['phoneNumber'])) 
    {
            $x->createDonor($_POST['username'],$_POST['lastname'],$_POST['firstname'],$_POST['userId'],$_POST['email'],$_POST['password'],$_POST['location'],$_POST['phoneNumber'],Event::retrieve($_POST['eventID']));
    }
    }
elseif(isset($_POST['Admin']))
{
    if (!empty($_POST['userId']) && !empty($_POST['username']) && !empty($_POST['firstname']) && !empty($_POST['lastname'])
        && !empty($_POST['email']) && !empty($_POST['Password']) && !empty($_POST['Location']) && !empty($_POST['phoneNumber']) 
        ) {
            $x->createAdmin($_POST['username'],$_POST['lastname'],$_POST['firstname'],$_POST['userId'],$_POST['email'],$_POST['password'],$_POST['location'],$_POST['phoneNumber']);

        // TODO Return the result as the POST request's response
    }
}
elseif(isset($_POST['Employee']))
{
    if(!empty($_POST['userId']) && !empty($_POST['username']) && !empty($_POST['firstname']) && !empty($_POST['lastname'])
    && !empty($_POST['email']) && !empty($_POST['Password']) && !empty($_POST['Location']) && !empty($_POST['phoneNumber'])&& !empty($_POST['title'])
    && !empty($_POST['salary'])&& !empty($_POST['workingHours'])
)
    {
        $x->createEmployee($_POST['username'],$_POST['lastname'],$_POST['firstname'],$_POST['userId'],$_POST['email'],$_POST['password'],$_POST['location'],$_POST['phoneNumber'],$_POST['title'],$_POST['salary'],$_POST['workingHours']);
    }
}
    return 0;
}

if(isset($_POST['retrieveDonor']))
{
    if(!empty($_POST['donorId']))
    {
        $x->retrieveDonor($_post['donorId']);
    }
}

if(isset($_POST['retrieveAdmin']))
{
    if(!empty($_POST['userId']))
    {
        $x->retrieveuser($_post['userId']);
    }
}

if(isset($_post['deleteDonor']))
{
    if(!empty($_POST['donorId']))
    {
        $x->deleteDonor($_post['donorId']);
    }
}

if(isset($_post['deleteAdmin']))
{
    if(!empty($_POST['userId']))
    {
        $x->deleteAdmin($_post['userId']);
    }
}

if(isset($_post['deleteEmployee']))
{
    if(!empty($_POST['userId']))
    {
        $x->deleteEmployee($_post['userId']);
    }
}

if(isset($_post['fullName']))
{
    if(!empty($_post['userId']))
    {
        $x->getFullName($_post['userId']);
    }
}

if(isset($_post['changePassword']))
{
    if(!empty($_post['userId'])&&!empty($_post['old'])&&!empty($_post['new']))
    {
        $x->changePassword($_post['userId'],$_post['old'],$_post['new']);
    }
}


if(isset($_post['updateAdmin']))
{
    $updates = array();
    if(!empty($_post['userId']))
    {
        if(isset($_POST['username']))
        {
            $updates['username'] = $_post['username'];
        }
        if(isset($_POST['email']))
        {
            $updates['email'] = $_post['email'];
        }

        if(isset($_post['firstname']))
        {
            $updates['firstname'] = $_post['firstname'];
        }
        if(isset($_post['lastname']))
        {
            $updates['lastname'] = $_post['lastname'];
        }
        if(isset($_Location))
        {
            $updates['Location'] = $_post['Location'];
        }   
        if(isset($_post['phoneNumber']))
        {
            $updates['phoneNumber'] = $_post['phoneNumber'];
        }

        Admin::update($_post['userId'],$updates);

    }
}


if(isset($_post['updateDonor']))
{
    $updates = array();
    if(!empty($_post['userId']))
    {
        if(isset($_POST['username']))
        {
            $updates['username'] = $_post['username'];
        }
        if(isset($_POST['email']))
        {
            $updates['email'] = $_post['email'];
        }

        if(isset($_post['firstname']))
        {
            $updates['firstname'] = $_post['firstname'];
        }
        if(isset($_post['lastname']))
        {
            $updates['lastname'] = $_post['lastname'];
        }
        if(isset($_Location))
        {
            $updates['Location'] = $_post['Location'];
        }   
        if(isset($_post['phoneNumber']))
        {
            $updates['phoneNumber'] = $_post['phoneNumber'];
        }

        if(isset($_post['phoneNumber']))
        {
            $updates['phoneNumber'] = $_post['phoneNumber'];
        }
        Donor::update($_post['userId'],$updates);
    }
}

if(isset($_post['updateDonor']))
{
    $updates = array();
    if(!empty($_post['userId']))
    {
        if(isset($_POST['username']))
        {
            $updates['username'] = $_post['username'];
        }
        if(isset($_POST['email']))
        {
            $updates['email'] = $_post['email'];
        }

        if(isset($_post['firstname']))
        {
            $updates['firstname'] = $_post['firstname'];
        }
        if(isset($_post['lastname']))
        {
            $updates['lastname'] = $_post['lastname'];
        }
        if(isset($_Location))
        {
            $updates['Location'] = $_post['Location'];
        }   
        if(isset($_post['phoneNumber']))
        {
            $updates['phoneNumber'] = $_post['phoneNumber'];
        }

        if(isset($_post['phoneNumber']))
        {
            $updates['phoneNumber'] = $_post['phoneNumber'];
        }
        if(isset($_post['title']))
        {
            $updates['title'] = $_post['title'];
        }
        if(isset($_post['salary']))
        {
            $updates['salary'] = $_post['salary'];
        }
        if(isset($_post['workingHours']))
        {
            $updates['workingHours'] = $_post['workingHours'];
        }
        EmployeeModel::update($_post['userId'],$updates);
    }
}