<?php
class OrderDataModel {
    public $id;
    public $order_number;
    public $user_id;
    public $personal_data_id;
    public $address_id;
    public $delivery_method_id;
    public $payment_method_id;
    public $total_price;
    public $comment;
    private $items;

    function __construct($id, $order_number, $user_id, $personal_data_id, $address_id, $delivery_method_id, $payment_method_id, $comment) {
        $this->id = $id;
        $this->order_number = $order_number;
        $this->user_id = $user_id;
        $this->personal_data_id = $personal_data_id;
        $this->address_id = $address_id;
        $this->delivery_method_id = $delivery_method_id;
        $this->payment_method_id = $payment_method_id;
        $this->total_price = 0.00;
        $this->comment = $comment;
        $this->items = [];
    }

    function updateDbOrderPrice($conn)
    {
        $sql = "update order_data set total_price = '" . $this->total_price . "' where id = '" . $this->id . "'";
        $result = mysqli_query($conn, $sql);
        
        return $result;
    }
    function defineOrderTotalPrice($conn, $discount_code)
    {
        $records = $this->getItemsFromDB($conn);

        foreach ($this->items as $item) {
            foreach ($records as $record) {
                if($item['id'] === $record['id'])
                {              
                    $itemsAmount = $this->getPossibleItemsAmountToOrder($item['amount'], $record['amount']);

                    $this->total_price += floatval($record['price']) * $itemsAmount;
                }
            }
        }

        $this->applyDiscount($discount_code, $conn);
        $this->applyDeliveryPayment($conn);
    
        $this->total_price = round($this->total_price, 2);
    }
    function addItemsToOrder($conn)
    {   
        $records = $this->getItemsFromDB($conn);

        foreach ($this->items as $item) {
            foreach ($records as $record) {
                if($item['id'] === $record['id'])
                {
                    $itemsAmount = $this->getPossibleItemsAmountToOrder($item['amount'], $record['amount']);

                    for($i = 0; $i < $itemsAmount; $i++)
                    {
                        $sql = "insert into order_item (order_id, item_id) values ('" .$this->id. "','" . $record['id'] . "')"; 
                        mysqli_query($conn, $sql);
                    }

                    $newAmountInStorage = intval($record['amount']) - intval($itemsAmount);
                    $sql = "update item set amount = '" . $newAmountInStorage . "' where id = '" . $record['id'] . "'";
                    mysqli_query($conn, $sql);
                }
            }
        }  
    }
    private function getPossibleItemsAmountToOrder($wantedAmount, $amountInStorage)
    {
        //ilość zamówionych produktów jest większa, niż dostępna w magazynie -> oddajemy wszystkie
        $amount = intval($wantedAmount);

        if(intval($amountInStorage) < intval($wantedAmount))
        {
            $amount = intval($amountInStorage);                    
        }

        return $amount;
    }
    private function getItemsFromDB($conn)
    {
        $item_id_list = array_column($this->items, 'id');
        $ids_str = implode(',', $item_id_list);  

        $sql = "select * from item where id in ($ids_str)";
        $result = mysqli_query($conn, $sql);
        $records = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $records;
    }
    private function applyDiscount($discountCode, $conn)
    {
        $sql = "select * from discount_code where code = '" . $discountCode . "' and is_active = 1";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        
        if(mysqli_num_rows($result) != 0){
            $discountPercentage = intval($row["discount_percentage"]);
            $discount = floatval($this->total_price  * ($discountPercentage / 100));            
            $this->total_price -= $discount;
        }
    }
    private function applyDeliveryPayment($conn)
    {
        $sql = "select * from delivery_method where id = " . $this->delivery_method_id;
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        if(mysqli_num_rows($result) != 0){           
            $this->total_price += floatval($row['price']);
        }
        else{
            //może jakimś cudem nie znajdziemy wybrany sposób dostawy,
            //wtedy dodamy do ceny 25zł aby nie była darmowa 
            $this->total_price += 25.00;
        }
    }
    public function setItemSet($items){
        $this->items = $items;
    }
    function insertIntoDB($conn){
        $sql = "insert into order_data (order_number, user_id, personal_data_id,".
         "address_id, delivery_method_id, payment_method_id, comment) values ('".
         $this->order_number."','".$this->user_id."','".
         $this->personal_data_id."','".$this->address_id."','".
         $this->delivery_method_id."','".$this->payment_method_id.
         "','".$this->comment."')";   

        $result = mysqli_query($conn, $sql);
        
        return $result;
    }
    function getId() {
        return $this->id;
    }
    public function setId($id){
        $this->id = $id;
    }
    function getOrderNumber() {
        return $this->order_number;
    }
    public function setOrderNumber($order_number){
        $this->order_number = $order_number;
    }
    function getUserId() {
        return $this->user_id;
    }
    public function setUserId($user_id){
        $this->user_id = $user_id;
    }
    function getPersonalDataId() {
        return $this->personal_data_id;
    }
    function getAddressId() {
        return $this->address_id;
    }
    function getDeliveryMethodId() {
        return $this->delivery_method_id;
    }
    function getPaymentMethodId() {
        return $this->payment_method_id;
    }
    function getTotalPrice() {
        return $this->total_price;
    }
    public function setTotalPrice($total_price){
        $this->total_price = $total_price;
    }
    function getComment() {
        return $this->comment;
    }

}

?>