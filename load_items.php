<?php

$data=[];
$data['msg'] = "";

//$data["item_ids"] = $_GET["item_ids"];//"got and returned!";
if($_SERVER["REQUEST_METHOD"] == "GET") {
    $conn = mysqli_connect("localhost", "root", "", "smartbees_zadanie_db");

    $sql = "select * from user where login = '".$_POST['login']."' and password = '".$_POST['pass']."'";
    $result = mysqli_query($conn, $sql);
}

$ids_str = implode(',', $_GET["item_ids"]);

//$data = $_GET["item_ids"][0]. " has been received!";

echo(json_encode($ids_str));

?>