<?php

class Magenest_Vtigerintegration_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
//        $model = Mage::getModel('vtigerintegration/sync_lead')->sync(9);
        echo "demo";
        Mage::log('Edmo', null, 'mylog.log', true);
    }
}