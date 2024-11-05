<?php

class UserController{

}

if (isset($_POST['createUser'])) {
    if(isset($_POST['Donor'])){
        if (!empty($_POST['userId']) && !empty($_POST['donorID']) && !empty($_POST['username']) && !empty($_POST['firstname']) && !empty($_POST['lastname'])
        && !empty($_POST['email']) && !empty($_POST['Password']) 
        ) {
        // $cart = Cart::get_by_user_id($_POST['userId'])[0];
        // Cart::add_item_to_cart($cart->id, $_POST['itemId']);
        // TODO Return the result as the POST request's response
    };
}
    return 0;
}


//- donorID: int
// - username: String
// -firstname: String
// -lastname: String
// -userID:int
// - email: String
// -username:ID
// -Password:String
// -Location:List<Location>
// -phoneNumber:int