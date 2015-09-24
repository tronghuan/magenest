<?php
class Magenest_Vtigerintegration_Model_Sync_Organization extends Magenest_Vtigerintegration_Model_Connector {

    public $vtiger_fields;
    public $magento_fields;
    public $_type;

    public function __construct() {
        parent::__construct();
        $this->_type = 'Organizations';
        $fields = $this->getOrganizations();
        $this->_magento_fields = unserialize($fields['magento']);
        $this->_vtiger_fields = unserialize($fields['vtigerintegration']);
    }

    //get fields from to table Organizations Vtiger
    public function getOrganizations() {
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
    public function sync($id) {
        $model = Mage::getModel('customer/customer')->load($id);
        $name = $model->getLastname();
        $email = $model->getEmail();
        $phone = $model->getDefaultBillingAddress()->getTelephone();

        $object = array('accountname' => $name, 'email1' => $email, 'phone' => $phone);


        $account_id = $this->searchRecords('Accounts', 'email1', $email);

        if ($account_id === false) {
            $account_id = $this->sendCurlRequest($object, 'Accounts');
            return $account_id;
        } else {
            $account_id = $this->updateRecords('Accounts', $account_id, $object);
            return $account_id;
        }
    }
    
    /*
     * Delete Records Organization
     */

    public function deleteRecordOrganization($email) {
        $accountId = $this->searchRecords('Accounts', 'email1', $email);
        if ($accountId)
            $this->deleteRecords($accountId);
    }

}
