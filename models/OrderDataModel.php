<?php
class OrderDataModel {
    public $id;
    public $order_number;
    public $user_id;
    public $personal_data_id;
    public $address_id;
    public $delivery_method_id;
    public $payment_method_id;
    public $comment;

    function __construct($id, $order_number, $user_id, $personal_data_id, $address_id, $delivery_method_id, $payment_method_id, $comment) {
        $this->id = $id;
        $this->order_number = $order_number;
        $this->user_id = $user_id;
        $this->personal_data_id = $personal_data_id;
        $this->address_id = $address_id;
        $this->delivery_method_id = $delivery_method_id;
        $this->payment_method_id = $payment_method_id;
        $this->comment = $comment;
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
    function getComment() {
        return $this->comment;
    }

}

?>