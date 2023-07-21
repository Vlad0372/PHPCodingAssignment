<?php
class UserModel {
    public $id;
    public $login;
    public $password;
    public $default_personal_data_id;
    public $default_address_id;
    public $signed_to_newsletter;

    function __construct($id, $login, $password, $default_personal_data_id, $default_address_id, $signed_to_newsletter) {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
        $this->default_personal_data_id = $default_personal_data_id;
        $this->default_address_id = $default_address_id;
        if($signed_to_newsletter === 'true'){
            $this-> signed_to_newsletter = 1;
        }else{
            $this-> signed_to_newsletter = 0;
        }
    }
    function getId() {
        return $this->id;
    }
    public function setId($id){
        $this->id = $id;
    }
    function getLogin() {
        return $this->login;
    }
    function getPassword() {
        return $this->password;
    }
    function getDefaultPersonalDataId() {
        return $this->default_personal_data_id;
    }
    function getDefaultAddressId() {
        return $this->default_address_id;
    }
    function getSignedToNewsletter() {
        return $this->signed_to_newsletter;
    }

}

?>