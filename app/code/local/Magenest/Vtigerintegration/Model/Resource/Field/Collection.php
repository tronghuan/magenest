<?php

class Magenest_Vtigerintegration_Model_Resource_Field_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {

    public function _construct() {
        parent::_construct();

        $this->_init('vtigerintegration/field');
    }

}

?>