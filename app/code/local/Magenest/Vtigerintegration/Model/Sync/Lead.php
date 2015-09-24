<?php

class Magenest_Vtigerintegration_Model_Sync_Lead extends Magenest_Vtigerintegration_Model_Connector {

    public $vtiger_fields;
    public $magento_fields;
    public $_type;

    public function __construct() {
        parent::__construct();
        $this->_type = 'Leads';
        $fields = $this->getLeads();
        $this->_magento_fields = unserialize($fields['magento']);
        $this->_vtiger_fields = unserialize($fields['vtigerintegration']);
    }

    //get fields from to table Leads Vtiger
    public function getLeads() {
        $model = Mage::getModel('vtigerintegration/field')->load($this->_type, 'type');

        $data = array();
        $vtiger = serialize(Mage::getModel('vtigerintegration/connector')->getField($this->_type));
        $magento = serialize($model->getMagentoFields($this->_type));
        if (!$model->getId()) {
            $data['vtigerintegration'] = $vtiger;
            $data['magento'] = $magento;
            $data['type'] = $this->_type;
            $model->setData($data);
            $model->save();
        } else {
            $data['vtigerintegration'] = $model->getData('vtiger');
            $data['magento'] = $model->getData('magento');
        }
        return $data;
    }

    //sendCurlRequest Leads
    public function sync($id, $update = false) {
        $customer = Mage::getModel('customer/customer')->load($id);
        $email = $customer->getEmail();
        $firstname = $customer->getFirstname();
        $lastname = $customer->getLastname();


        $object = array('email' => $email, 'firstname' => $firstname, 'lastname' => $lastname);
        //search recordId in Vtiger
        $leadId = $this->searchRecords('Leads', 'email', $email);

        if ($leadId === false) {
            $leadId = $this->sendCurlRequest($object, 'Leads');
            return $leadId;
        } else {
            $leadId = $this->updateRecords('Leads', $leadId, $object);
            return $leadId;
        }
    }

    /*
     * Delete Records Leads
     */

    public function deleteRecordLead($email) {
        $leadId = $this->searchRecords('Leads', 'email', $email);
        if ($leadId)
            $this->deleteRecords($leadId);
    }

}
