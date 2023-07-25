<?php

include("connection.php");

$data["is_active"] = "false";
$data["disount_percentage"] = "0";

if($_SERVER["REQUEST_METHOD"] == "GET" && $conn != false) {

    $sql = "select * from discount_code where code = '" . $_GET["code"] . "' and is_active = 1";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    
    if(mysqli_num_rows($result) != 0){

        $data["is_active"] = "true";
        $data["discount_percentage"] = $row["discount_percentage"];
    }
}

echo(json_encode($data));

?>