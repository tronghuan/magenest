<?php

class Magenest_Vtigerintegration_Model_Sync_Salesorder extends Magenest_Vtigerintegration_Model_Connector {

    public $vtiger_fields;
    public $magento_fields;
    public $_type;

    public function __construct() {
        parent::__construct();
        $this->_type = 'Saleorders';
        $fields = $this->getSalesorders();
        $this->_magento_fields = unserialize($fields['magento']);
        $this->_vtiger_fields = unserialize($fields['vtigerintegration']);
    }

    //get fields from to table Salesorders Vtiger
    public function getSalesorders() {
        $model = Mage::getModel('vtigerintegration/field')->load($this->_type, 'type');

        $data = array();
        $vtiger = serialize($model->getVtigerFields($this->_type));
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

    //sendCurlRequest SalesOrder
    public function sync($id) {


        $model = Mage::getModel('sales/order')->load($id);
        $customerId = $model->getCustomerId();
        $date = date('Y-m-d', strtotime($model->getCreateAt()));
        $email = $model->getCustomerEmail();
        $subject = $model->getIncrementId();
        $tax = $model->getTaxAmount();
        $discount_amount = $model->getDiscountAmount();
        $shipping_amount = $model->getShippingAmount();
        /*
         * 1. Get accountId, create new if not exitst
         * 2. Create new Contacts if not exitst
         */
        if ($customerId) {
            $account = Mage::getModel('vtigerintegration/sync_organization');
            $accountId = $account->sync($customerId);
            $contact = Mage::getModel('vtigerintegration/sync_contact');
            $contact->sync($customerId);
        } else {
            $account = array(
                'accountname' => $email
            );
            $accountId = $this->searchRecords('Accounts', 'email1', $email);
            if ($accountId === false) {
                $accountId = $this->sendCurlRequest($account, 'Accounts');
            }
        }

        $accountId = $this->searchRecords('Accounts', 'email1', $email);

        $b_address = $model->getBillingAddress();
        $country = Mage::getModel('directory/country');
        if (isset($b_address) && $b_address != "") {
            $dataBill = array(
                'billing_street' => $b_address->getStreetFull(),
                'billing_city' => $b_address->getCity(),
                'billing_state' => $b_address->getRegion(),
                'billing_postalcode' => $b_address->getPostcode(),
                'billing_country' => $b_address->getCountry() ? $country->loadByCode($b_address->getCountry())->getName() : $b_address->getCountry()
            );
        }
        //print_r($dataBill);
        $s_address = $model->getShippingAddress();
        if (isset($s_address) && $s_address != "") {
            $dataShip = array(
                'shipping_street' => $s_address->getStreetFull(),
                'shipping_city' => $s_address->getCity(),
                'shipping_state' => $s_address->getRegion(),
                'shipping_postalcode' => $s_address->getPostcode(),
                'shipping_country' => $s_address->getCountry() ? $country->loadByCode($s_address->getCountry())->getName() : $s_address->getCountry()
            );
        }

        //print_r($dataShip);


        $i = 0;
        foreach ($model->getAllItems() as $item) {
            $product_id = $item->getProductId();
            $product_code = $item->getName();
            $price = $item->getPrice();
            $qty = $item->getQtyOrdered();
            $code = $item->getSku();

            if ($price > 0) {
                // get productId
                $productId = Mage::getModel('vtigerintegration/sync_product')->sync($product_id);
                //$product_id = $productId[]
                $lineItem[$i] = array('productid' => $productId, 'quantity' => $qty, 'listprice' => $price);
                $i++;
                //print_r($productId);
            }
        }

        $data = array('subject' => $subject, 'sostatus' => 'Created', 'productid' => $productId,
            'account_id' => $accountId, 'bill_street' => $dataBill,
            'ship_street' => $dataShip, 'invoicestatus' => 'Paid',
            'hdnTaxType' => 'group', 'conversion_rate' => 1,
            'hdnS_H_Amount' => $shipping_amount,
            'LineItems' => $lineItem, 'currency_id' => '21x1', 'hdnDiscountAmount' => $discount_amount,
            'assigned_user_id' => '19x1');
        $model = $this->sendCurlRequest($data, 'SalesOrder');
        return $model;
    }

}
