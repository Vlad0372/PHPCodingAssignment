<?php

$delivery_methods = json_decode($_GET["delivery_methods"]);

$data["delivery_method_id_list"] = array_column($delivery_methods, 'id');

if($_SERVER["REQUEST_METHOD"] == "GET") {
    $ids_str = implode(',', $data["delivery_method_id_list"]);

    $conn = mysqli_connect("localhost", "root", "", "smartbees_zadanie_db");

    $sql = "select * from delivery_method where id in ($ids_str)";
    $result = mysqli_query($conn, $sql);
    $records = mysqli_fetch_all($result, MYSQLI_ASSOC);
   
    $data["delivery_method_name_list"] = array_column($records, 'name');
    $data["delivery_method_file_name_list"] = array_column($records, 'file_name');
    $data["delivery_method_price_list"] = array_column($records, 'price');
}

echo(json_encode($data));

?>