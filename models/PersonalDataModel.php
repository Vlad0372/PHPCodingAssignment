<?php
class PersonalDataModel {
    public $id;
    public $first_name;
    public $last_name;
    public $phone_number;

    function __construct($id, $first_name, $last_name, $phone_number) {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->phone_number = $phone_number;
    }
    function getId() {
        return $this->id;
    }
    public function setId($id){
        $this->id = $id;
    }
    function getFirstName() {
        return $this->first_name;
    }
    function getLastName() {
        return $this->last_name;
    }
    function getPhoneNumber() {
        return $this->phone_number;
    }

}

?>