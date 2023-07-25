<?php
session_start();

include("connection.php");
include("models/PersonalDataModel.php");
include("models/AddressModel.php");
include("models/OrderDataModel.php");
include("models/UserModel.php");

$data["isValidationSucceed"] = true;

if($_SERVER["REQUEST_METHOD"] == "POST" && $conn != false) {

    validate();

    if($data["isValidationSucceed"] == true){

        $userId = null;
    
        $personal_data = new PersonalDataModel
        (
            0, 
            $_POST["firstname"], 
            $_POST["lastname"], 
            $_POST["telephone"]
        );
        $personal_data->insertIntoDB($conn);
        $personal_data->setId(mysqli_insert_id($conn));

        //tutaj trochę magic, podstawą numeru zamówienia służy liczba 10000 + autoinkrementowane id 
        //dowolnej tabeli, która się wypełnia przy każdym zamówieniu, ja wziąłem za podstawę personal_data id
        $orderNumber = 10000 + mysqli_insert_id($conn);

        $address = new AddressModel
        (
            0, 
            $_POST["country"], 
            $_POST["address"], 
            $_POST["postcode"], 
            $_POST["city"]
        );      
        $address->insertIntoDB($conn);
        $address->setId(mysqli_insert_id($conn));

        if(isset($_SESSION["user_id"])){
            $userId = $_SESSION["user_id"];
        }
        else if($_POST["createnewaccount"] === "true"){
            $user = new UserModel
            (
                0, 
                $_POST["login"], 
                $_POST["pass"], 
                $personal_data->getId(), 
                $address->getId(), 
                $_POST["getnewsletter"]
            );
            $user->insertIntoDB($conn);
            $user->setId(mysqli_insert_id($conn));
            $userId = $user->getId();    
        }
        
        $order = new OrderDataModel
        (
            0, 
            $orderNumber, 
            $userId, 
            $personal_data->getId(), 
            $address->getId(), 
            $_POST["deliverymethod"], 
            $_POST["paymentmethod"], 
            $_POST["discountcode"],
            $_POST["comment"]
        );
        $order->insertIntoDB($conn);
        $order->setId(mysqli_insert_id($conn));
        $order->setItemSet($_POST["items"]);
        $order->defineOrderTotalPrice($conn);
        $order->addItemsToOrder($conn);
        $order->updateDbOrderPrice($conn);
        
        $data["order_price"] = $order->getTotalPrice();
        $data["orderNumber"] = $order->getOrderNumber();

        session_unset();
    }
}
echo(json_encode($data));

function validate()
{
    global $data;

    if($_POST["createnewaccount"] === "true"){

        if(isExistingUser()){
            $data["msg"] = "Użytkownik z takim loginem już istnieje!";
            $data["isValidationSucceed"] = false;
        }
        else if(!preg_match("/[a-zA-Z0-9 ]{5,32}/", $_POST["login"])){
            $data["msg"] = "Login nie spełnia wymagań!";
            $data["isValidationSucceed"] = false;
        }
        else if(!preg_match("/[a-zA-Z0-9_]{8,15}/", $_POST["pass"])){
            $data["msg"] = "Hasło nie spełnia wymagań!";
            $data["isValidationSucceed"] = false;
        }
        else if($_POST["pass"] !== $_POST["confirmpass"]){
            $data["msg"] = "Hasła się różnią!";
            $data["isValidationSucceed"] = false;
        }
    }
    
    if($data["isValidationSucceed"] == true){
    
        if(!preg_match("/[a-zA-Z0-9 ]{1,32}/", $_POST["firstname"])){
            $data["msg"] = "Imię nie spełnia wymagań!";
            $data["isValidationSucceed"] = false;
        }
        else if(!preg_match("/[a-zA-Z0-9 ]{1,32}/", $_POST["lastname"])){
            $data["msg"] = "Nazwisko nie spełnia wymagań!";
            $data["isValidationSucceed"] = false;
        }
        else if(strlen($_POST["address"]) < 5){
            $data["msg"] = "Adres nie spełnia wymagań!";
            $data["isValidationSucceed"] = false;
        }                   
        else if(!preg_match("/^\d{2}-\d{3}$/", $_POST["postcode"])){
            $data["msg"] = "Kod pocztowy nie spełnia wymagań!";
            $data["isValidationSucceed"] = false;
        }
        else if(strlen($_POST["city"]) < 4){
            $data["msg"] = "Miasto nie spełnia wymagań!";
            $data["isValidationSucceed"] = false;
        }
        else if(!preg_match("/^(?:\+48\s?)?\d{9}$/", $_POST["telephone"])){
            $data["msg"] = "Numer telefonu nie spełnia wymagań!";
            $data["isValidationSucceed"] = false;
        }
        else if(!isset($_POST["deliverymethod"])){
            $data["msg"] = "Proszę wybrać metodę dostawy";
            $data["isValidationSucceed"] = false;
        }
        else if(!isset($_POST["paymentmethod"])){
            $data["msg"] = "Proszę wybrać metodę płatności";
            $data["isValidationSucceed"] = false;
        }
        else if($_POST["acceptregulations"] === 'false'){
            $data["msg"] = "Zapoznanie się z regulaminem jest obowiązkowe";
            $data["isValidationSucceed"] = false;
        }
    }
}

function isExistingUser(){
    global $conn;

    $sql = "select * from user where login = '".$_POST["login"]."'";
    $result = mysqli_query($conn, $sql);
   
    if(mysqli_num_rows($result) == 0) {
        return false;
    }

    return true;
}
?>