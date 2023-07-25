<?php
session_start();

include("connection.php");
include("models/UserModel.php");

$data["msg"] = "Użytkownika z takim hasłem nie istnieje";
$data["isValidationSucceed"] = false;

if($_SERVER["REQUEST_METHOD"] == "POST" && $conn != false) {

    $sql = "select * from user where login = '".$_POST["login"]."'";
    $result = mysqli_query($conn, $sql);
    $user_row = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if(mysqli_num_rows($result) == 1) {

        if(password_verify($_POST["pass"], $user_row["password"])){

            $_SESSION["user_id"] = $user_row["id"];

            $sql = "select * from personal_data where id = '".$user_row["default_personal_data_id"]."'";
            $result = mysqli_query($conn, $sql);
            $personal_data_row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            $sql = "select * from address where id = '".$user_row["default_address_id"]."'";
            $result = mysqli_query($conn, $sql);
            $address_row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            $data["first_name"] = $personal_data_row["first_name"];
            $data["last_name"] = $personal_data_row["last_name"];
            $data["phone_number"] = $personal_data_row["phone_number"];

            $data["country"] = $address_row["country"];
            $data["street"] = $address_row["street"];
            $data["post_code"] = $address_row["post_code"];
            $data["city"] = $address_row["city"];

            $data["msg"] = "Użytkownik ".$user_row["login"]." został zalogowany pomyślnie";   
            $data["isValidationSucceed"] = true;
        }
        else{
            $data["msg"] = "Nieprawidłowe hasło!";
        }
             
    }
}

echo(json_encode($data));

?>