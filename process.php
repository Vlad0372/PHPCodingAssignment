<?php

include('models/PersonalDataModel.php');
include('models/AddressModel.php');
include('models/OrderDataModel.php');
include('models/UserModel.php');

$data = [];

$data['isValidationSucceed'] = true;
$data['msg'] = "";
$data['orderNumber'] = "";

validate($data);

if($data['isValidationSucceed'] == true){

    $conn = mysqli_connect("localhost", "root", "", "smartbees_zadanie_db");
    $userId = null;
  
    $user_personal_data = new PersonalDataModel(0, $_POST['firstname'], $_POST['lastname'], $_POST['telephone']);
    $sql = "insert into personal_data (first_name, last_name, phone_number) values ('".$user_personal_data->getFirstName()."','".$user_personal_data->getLastName()."','".$user_personal_data->getPhoneNumber()."')";  
    $result = mysqli_query($conn, $sql);
    $user_personal_data->setId(mysqli_insert_id($conn));

    $user_address = new AddressModel(0, $_POST['country'], $_POST['address'], $_POST['postcode'], $_POST['city']);      
    $sql = "insert into address (country, street, post_code, city) values ('".$user_address->getCountry()."','".$user_address->getStreet()."','".$user_address->getPostCode()."','".$user_address->getCity()."')";      
    $result = mysqli_query($conn, $sql);
    $user_address->setId(mysqli_insert_id($conn));

    if($_POST['createnewaccount'] === 'true'){
        $user = new UserModel(0, $_POST['login'], $_POST['pass'], $user_personal_data->getId(), $user_address->getId(), $_POST['getnewsletter']);
        $sql = "insert into user (login, password, default_personal_data_id, default_address_id, signed_to_newsletter) values ('".$user->getLogin()."','".$user->getPassword()."','".$user->getDefaultPersonalDataId()."','".$user->getDefaultAddressId()."','".$user->getSignedToNewsletter()."')";      
        $result = mysqli_query($conn, $sql);
        $user->setId(mysqli_insert_id($conn));
        $userId = $user->getId();    
    }

    $order_data = new OrderDataModel(0, (10000 + mysqli_insert_id($conn)), $userId, $user_personal_data->getId(), $user_address->getId(), $_POST['deliverymethod'], $_POST['paymentmethod'], $_POST['comment']);
    $sql = "insert into order_data (order_number, user_id, personal_data_id, address_id, delivery_method_id, payment_method_id, comment) values ('".$order_data->getOrderNumber()."','".$order_data->getUserId()."','".$order_data->getPersonalDataId()."','".$order_data->getAddressId()."','".$order_data->getDeliveryMethodId()."','".$order_data->getPaymentMethodId()."','".$order_data->getComment()."')";      
    $result = mysqli_query($conn, $sql);
    $order_data->setId(mysqli_insert_id($conn));

    $data['orderNumber'] = $order_data->getOrderNumber(); 
}

echo(json_encode($data));

function validate(&$data)
{
    if($_POST['createnewaccount'] === 'true'){

        if(!preg_match('/[a-zA-Z0-9 ]{5,32}/', $_POST['login'])){
            $data['msg'] = "Login nie spełnia wymagań!";
            $data['isValidationSucceed'] = false;
        }
        else if(!preg_match('/[a-zA-Z0-9_]{8,15}/', $_POST['pass'])){
            $data['msg'] = "Hasło nie spełnia wymagań!";
            $data['isValidationSucceed'] = false;
        }
        else if($_POST['pass'] !== $_POST['confirmpass']){
            $data['msg'] = "Hasła się różnią!";
            $data['isValidationSucceed'] = false;
        }
    }
    
    if($data['isValidationSucceed'] == true){
    
        if(!preg_match('/[a-zA-Z0-9 ]{1,32}/', $_POST['firstname'])){
            $data['msg'] = "Imię nie spełnia wymagań!";
            $data['isValidationSucceed'] = false;
        }
        else if(!preg_match('/[a-zA-Z0-9 ]{1,32}/', $_POST['lastname'])){
            $data['msg'] = "Nazwisko nie spełnia wymagań!";
            $data['isValidationSucceed'] = false;
        }
        else if(strlen($_POST['address']) < 5){
            $data['msg'] = "Adres nie spełnia wymagań!";
            $data['isValidationSucceed'] = false;
        }                   
        else if(!preg_match('/^\d{2}-\d{3}$/', $_POST['postcode'])){
            $data['msg'] = "Kod pocztowy nie spełnia wymagań!";
            $data['isValidationSucceed'] = false;
        }
        else if(strlen($_POST['city']) < 4){
            $data['msg'] = "Miasto nie spełnia wymagań!";
            $data['isValidationSucceed'] = false;
        }
        else if(!preg_match('/^(?:\+48\s?)?\d{9}$/', $_POST['telephone'])){
            $data['msg'] = "Numer telefonu nie spełnia wymagań!";
            $data['isValidationSucceed'] = false;
        }
        else if(!isset($_POST['deliverymethod'])){
            $data['msg'] = "Proszę wybrać metodę dostawy";
            $data['isValidationSucceed'] = false;
        }
        else if(!isset($_POST['paymentmethod'])){
            $data['msg'] = "Proszę wybrać metodę płatności";
            $data['isValidationSucceed'] = false;
        }
        else if($_POST['acceptregulations'] === 'false'){
            $data['msg'] = "Zapoznanie się z regulaminem jest obowiązkowe";
            $data['isValidationSucceed'] = false;
        }
    }
}

?>