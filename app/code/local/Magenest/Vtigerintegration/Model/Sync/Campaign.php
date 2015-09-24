<?php

class Magenest_Vtigerintegration_Model_Sync_Campaign extends Magenest_Vtigerintegration_Model_Connector {

    public $vtiger_fields;
    public $magento_fields;
    public $_type;

    public function __construct() {
        parent::__construct();
        $this->_type = 'Campaigns';
        $fields = $this->getCampaigns();
        $this->_magento_fields = unserialize($fields['magento']);
        $this->_vtiger_fields = unserialize($fields['vtigerintegration']);
    }

    //get fields from to table Campaigns Vtiger
    public function getCampaigns() {
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
    public function sync($id) {
        $model = Mage::getModel('catalogrule/rule')->load($id);
        $nameCam = $model->getName();
        $status = $model->getIs_active();
        $description = $model->getDescription();
        $sort_order = $model->getSort_order();
        $simple_action = $model->getSimple_action();
        $discount_amount = $model->getDiscount_amount();
        $closingdate = $model->getTo_date();

        if ($status == 0){
            $status = "Inactive";
        }  else {
            $status = "Active";
        }
        $object = array('campaignname' => $nameCam, 'closingdate' => $closingdate, 'campaignstatus' => $status);

        $CamId = $this->sendCurlRequest($object, 'Campaigns');
        return $CamId;
    }

    /*
     * Delete Records Campaign
     */

    public function deleteRecordLead($nameCam) {
        $campaignId = $this->searchRecords('Campaigns', 'campaignname', $nameCam);
        if ($campaignId)
            $this->deleteRecords($campaignId);
    }

}
