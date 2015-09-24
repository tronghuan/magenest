<?php

class Magenest_Vtigerintegration_Model_Sync_Contact extends Magenest_Vtigerintegration_Model_Connector {

    public $vtiger_fields;
    public $magento_fields;
    public $_type;

    public function __construct() {
        parent::__construct();
        $this->_type = 'Contacts';
        $fields = $this->getContacts();
        $this->_magento_fields = unserialize($fields['magento']);
        $this->_vtiger_fields = unserialize($fields['vtigerintegration']);
    }

    //get fields from to table Contacts Vtiger
    public function getContacts() {
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

    //sendCurlRequest Contacts
    public function sync($id, $update = null) {

        $customer = Mage::getModel('customer/customer')->load($id);
        $email = $customer->getEmail();
        $firstname = $customer->getFirstname();
        $lastname = $customer->getLastname();


        $object = array('email' => $email, 'firstname' => $firstname, 'lastname' => $lastname);
        // Check Email in Contact
        $contactId = $this->searchRecords('Contacts', 'email', $email);


        if ($contactId === false) {
            $contactId = $this->sendCurlRequest($object, 'Contacts');
            return $contactId;
        } else {
            $contactId = $this->updateRecords('Contacts', $contactId, $object);
            return $contactId;
        }
    }

    /*
     * Delete Records Contacts
     */

    public function deleteRecordLead($email) {
        $ContactId = $this->searchRecords('Contacts', 'email', $email);
        if ($ContactId)
            $this->deleteRecords($ContactId);
    }

}
