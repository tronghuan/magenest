<?php

$methods = array(
    array(
        'vtigerintegration' => 'firstname',
        'magento' => 'firstname',
        'status' => 1,
        'type' => 'lead',
        'name' => 'First Name'
    ),
    array(
        'vtigerintegration' => 'lastname',
        'magento' => 'lastname',
        'status' => 1,
        'type' => 'lead',
        'name' => 'Last Name'
    ),
    array(
        'vtigerintegration' => 'phone',
        'magento' => 'bill_telephone',
        'status' => 1,
        'type' => 'lead',
        'name' => 'Phone'
    ),
    array(
        'vtigerintegration' => 'email',
        'magento' => 'email',
        'status' => 1,
        'type' => 'lead',
        'name' => 'Email',
    ),
    array(
        'vtigerintegration' => 'hdnDiscountAmount',
        'magento' => 'discount_amount',
        'status' => 1,
        'type' => 'invoice',
        'name' => 'Discount Amount'
    ),
    array(
        'vtigerintegration' => 'hdnS_H_Amount',
        'magento' => 'discount_amount',
        'status' => 1,
        'type' => 'invoice',
        'name' => ''
    ),
    array(
        'vtigerintegration' => 'bill_street',
        'magento' => 'bill_street',
        'status' => 1,
        'type' => 'invoice',
        'name' => ''
    ),
    array(
        'vtigerintegration' => 'bill_city',
        'magento' => 'bill_city',
        'status' => 1,
        'type' => 'invoice',
        'name' => ''
    ),
    array(
        'vtigerintegration' => 'campaignname',
        'magento' => 'name',
        'status' => 1,
        'type' => 'campaign',
        'name' => ''
    ),
    array(
        'vtigerintegration' => 'closingdate',
        'magento' => 'to_date',
        'status' => 1,
        'type' => 'campain',
        'name' => ''
    ),
    array(
        'vtigerintegration' => '',
        'magento' => '',
        'status' => 1,
        'type' => '',
        'name' => ''
    ),
    array(
        'vtigerintegration' => '',
        'magento' => '',
        'status' => 1,
        'type' => '',
        'name' => ''
    ),
    array(
        'vtigerintegration' => '',
        'magento' => '',
        'status' => 1,
        'type' => '',
        'name' => ''
    ),
    array(
        'vtigerintegration' => '',
        'magento' => '',
        'status' => 1,
        'type' => '',
        'name' => ''
    ),
    array(
        'vtigerintegration' => '',
        'magento' => '',
        'status' => 1,
        'type' => '',
        'name' => ''
    ),
    array(
        'vtigerintegration' => '',
        'magento' => '',
        'status' => 1,
        'type' => '',
        'name' => ''
    ),
    array(
        'vtigerintegration' => '',
        'magento' => '',
        'status' => 1,
        'type' => '',
        'name' => ''
    ),
    array(
        'vtigerintegration' => '',
        'magento' => '',
        'status' => 1,
        'type' => '',
        'name' => ''
    ),
    array(
        'vtigerintegration' => '',
        'magento' => '',
        'status' => 1,
        'type' => '',
        'name' => ''
    ),
    array(
        'vtigerintegration' => '',
        'magento' => '',
        'status' => 1,
        'type' => '',
        'name' => ''
    ),
    array(
        'vtigerintegration' => '',
        'magento' => '',
        'status' => 1,
        'type' => '',
        'name' => ''
    ),
    array(
        'vtigerintegration' => '',
        'magento' => '',
        'status' => 1,
        'type' => '',
        'name' => ''
    ),
    array(
        'vtigerintegration' => '',
        'magento' => '',
        'status' => 1,
        'type' => '',
        'name' => ''
    ),
    array(
        'vtigerintegration' => '',
        'magento' => '',
        'status' => 1,
        'type' => '',
        'name' => ''
    ),
    
);

foreach ($methods as $method) {
    Mage::getModel('vtigerintegration/map')
            ->setData($method)
            ->save();
}