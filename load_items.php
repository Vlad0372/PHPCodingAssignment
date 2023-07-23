<?php

// $data=[];
// $data['msg'] = "";

// $data["items"] = $_GET["items"];//"got and returned!";

$items = json_decode($_GET["items"]);

$data["item_id_list"] = array_column($items, 'id');
$data["item_amount_list"] = array_column($items, 'amount');

if($_SERVER["REQUEST_METHOD"] == "GET") {
    $ids_str = implode(',', $data["item_id_list"]);

    $conn = mysqli_connect("localhost", "root", "", "smartbees_zadanie_db");

    $sql = "select * from item where id in ($ids_str)";
    $result = mysqli_query($conn, $sql);
    $records = mysqli_fetch_all($result, MYSQLI_ASSOC);
   
    
    $data["item_name_list"] = array_column($records, 'name');
    $data["item_file_name_list"] = array_column($records, 'file_name');
    $data["item_price_list"] = array_column($records, 'price');
}


//$data=$_GET["items['id']"];

//$data = $_GET["item_id_list"][0]. " has been received!";


//echo(json_encode($data["item_id_list"]));
echo(json_encode($data));
//echo(json_encode($ids_str));

?>