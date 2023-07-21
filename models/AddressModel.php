<?php
class AddressModel {
    public $id;
    public $country;
    public $street;
    public $post_code;
    public $city;

    function __construct($id, $country, $street, $post_code, $city) {
        $this->id = $id;
        $this->country = $country;
        $this->street = $street;
        $this->post_code = $post_code;
        $this->city = $city;
    }
    function getId() {
        return $this->id;
    }
    public function setId($id){
        $this->id = $id;
    }
    function getCountry() {
        return $this->country;
    }
    function getStreet() {
        return $this->street;
    }
    function getPostCode() {
        return $this->post_code;
    }
    function getCity() {
        return $this->city;
    }

}

?>