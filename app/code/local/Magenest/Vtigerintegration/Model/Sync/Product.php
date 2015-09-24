<?php

class Magenest_Vtigerintegration_Model_Sync_Product extends Magenest_Vtigerintegration_Model_Connector {

    public $vtiger_fields;
    public $magento_fields;
    public $_type;

    public function __construct() {
        parent::__construct();
        $this->_type = 'Products';
        $fields = $this->getProducts();
        $magento_fields = unserialize($fields['magento']);
        $vtiger_fields = unserialize($fields['vtigerintegration']);
    }

    //get fields from to table Products Vtiger
    public function getProducts() {
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

    //sendCurlRequest Products
    public function sync($id, $update = false) {

        $product = Mage::getModel('catalog/product')->load($id);
        $category = Mage::getModel('catalog/category');
        $name = $product->getName();
        $code = $product->getSku();
        $perunit = $product->getStockItem()->getQty();
        $price = $product->getPrice();
        $status = $product->getStatus();

        $object = array('productname' => $name, 'productcode' => $code, 'qty_per_unit' => $perunit, 'unit_price' => $price, 'qtyinstock' => $status);

        // check Sku vtiger
        $productId = $this->searchRecords('Products', 'productcode', $code);

        if ($productId === false) {
            $productId = $this->sendCurlRequest($object, 'Products');
        } elseif ($update && $productId) {
            $productId = $this->updateRecords('Products', $productId, $object);
        }
        return $productId;
    }

    public function deleteRecordProduct($code) {
        $leadId = $this->searchRecords('Products', 'productcode', $code);
        if ($leadId)
            $this->deleteRecords($leadId);
    }

}