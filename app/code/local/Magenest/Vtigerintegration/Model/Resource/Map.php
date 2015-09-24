<?php

class Magenest_Vtigerintegration_Model_Resource_Map extends Mage_Core_Model_Resource_Db_Abstract {

    public function _construct() {
        $this->_init('vtigerintegration/map', 'id');
    }

}
