<?php

include("connection.php");

$data["isLoadingSucceed"] = false;

if($_SERVER["REQUEST_METHOD"] == "GET" && $conn != false){

    $items = json_decode($_GET["items"]);

    $data["item_id_list"] = array_column($items, "id");
    $data["item_amount_list"] = array_column($items, "amount");

    $ids_str = implode(",", $data["item_id_list"]);

    $sql = "select * from item where id in ($ids_str)";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) != 0){

        $records = mysqli_fetch_all($result, MYSQLI_ASSOC);
   
        $data["item_name_list"] = array_column($records, "name");
        $data["item_file_name_list"] = array_column($records, "file_name");
        $data["item_price_list"] = array_column($records, "price");
        $data["isLoadingSucceed"] = true;
    } 
}

echo(json_encode($data));

?>