<?php

class Magenest_Vtigerintegration_Model_Field extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('vtigerintegration/field');
    }

    public function getMagentoFields($type) {
        $magentoFields = [];
        switch ($type) {
            case 'Contacts':
                $magentoFields = [
                    'entity_id' => 'ID',
                    'entity_type_id' => 'Type ID',
                    'attribute_set_id' => 'Attribute Set ID',
                    'website_id' => 'Website ID',
                    'email' => 'Email',
                    'group_id' => 'Group ID',
                    'store_id' => 'Store ID',
                    'is_active' => 'Active',
                    'created_at' => 'Create At',
                    'update_at' => 'Update At',
                    'is_active' => 'is Active',
                    'created_in' => 'Created in',
                    'firstname' => 'First name',
                    'middlename' => 'Middle Name/Initial',
                    'lastname' => 'Last name',
                ];
                break;
            case 'Leads':
                $magentoFields = [
                    'entity_id' => 'ID',
                    'entity_type_id' => 'Type ID',
                    'attribute_set_id' => 'Attribute Set ID',
                    'website_id' => 'Website ID',
                    'email' => 'Email',
                    'group_id' => 'Group ID',
                    'store_id' => 'Store ID',
                    'is_active' => 'Active',
                    'created_at' => 'Create At',
                    'update_at' => 'Update At',
                    'is_active' => 'is Active',
                    'created_in' => 'Created in',
                    'firstname' => 'First name',
                    'middlename' => 'Middle Name/Initial',
                    'lastname' => 'Last name',
                ];
                break;
            case 'Organizations':
                $magentoFields = [
                    'entity_id' => 'ID',
                    'entity_type_id' => 'Type ID',
                    'attribute_set_id' => 'Attribute Set ID',
                    'website_id' => 'Website ID',
                    'email' => 'Email',
                    'group_id' => 'Group ID',
                    'store_id' => 'Store ID',
                    'is_active' => 'Active',
                    'created_at' => 'Create At',
                    'update_at' => 'Update At',
                    'is_active' => 'is Active',
                    'created_in' => 'Created in',
                    'firstname' => 'First name',
                    'middlename' => 'Middle Name/Initial',
                    'lastname' => 'Last name',
                ];
                break;
            case 'Products':
                $magentoFields = [
                    'name' => 'Name',
                    'description' => 'Description',
                    'short_description' => 'Short Description',
                    'sku' => 'SKU',
                    'weight' => 'Weight',
                    'news_from_date' => 'Set Product as New from Date',
                    'news_to_date' => 'Set Product as New to Date',
                    'status' => 'Status',
                    'country_of_manufacture' => 'Country of Manufacture',
                    'url_key' => 'URL Key',
                    'price' => 'Price',
                    'special_price' => 'Special Price',
                    'special_from_date' => 'Special From Date',
                    'special_to_date' => 'Special To Date',
                    'stock_stock_id' => 'Stock Id',
                    'stock_qty' => 'Qty',
                    'meta_title' => 'Meta Title',
                    'meta_keyword' => 'Meta Keywords',
                    'meta_description' => 'Meta Description',
                    'tax_class_id' => 'Tax Class',
                    'image' => 'Base Image',
                    'small_image' => 'Small Image',
                    'thumbnail' => 'Thumbnail',
                ];
                break;
            case 'Saleorders':
                $magentoFields = [
                    'entity_id' => 'ID',
                    'state' => 'State',
                    'status' => 'Status',
                    'coupon_code' => 'Coupon Code',
                    'coupon_rule_name' => 'Coupon Rule Name',
                    'increment_id' => 'Increment ID',
                    'created_at' => 'Created At',
                    'company' => 'Company',
                    'customer_firstname' => 'Customer First Name',
                    'customer_middlename' => 'Customer Middle Name',
                    'customer_lastname' => 'Customer Last Name',
                    'bill_firstname' => 'Billing First Name',
                    'bill_middlename' => 'Billing Middle Name',
                    'bill_lastname' => 'Billing Last Name',
                    'bill_company' => 'Billing Company',
                    'bill_street' => 'Billing Street',
                    'bill_city' => 'Billing City',
                    'bill_region' => 'Billing State/Province',
                    'bill_postalcode' => 'Billing Zip/Postal Code',
                    'bill_telephone' => 'Billing Telephone',
                    'bill_country_id' => 'Billing Country',
                    'ship_firstname' => 'Shipping First Name',
                    'ship_middlename' => 'Shipping Middle Name',
                    'ship_lastname' => 'Shipping Last Name',
                    'ship_company' => 'Shipping Company',
                    'ship_street' => 'Shipping Street',
                    'ship_city' => 'Shipping City',
                    'ship_region' => 'Shipping State/Province',
                    'ship_postalcode' => 'Shipping Zip/Postal Code',
                    'ship_country_id' => 'Shipping Country',
                    'shipping_amount' => 'Shipping Amount',
                    'shipping_description' => 'Shipping Description',
                    'order_currency_code' => 'Currency Code',
                    'total_item_count' => 'Total Item Count',
                    'store_currency_code' => 'Store Currency Code',
                    'shipping_discount_amount' => 'Shipping Discount Amount',
                    'discount_description' => 'Discount Description',
                    'shipping_method' => 'Shipping Method',
                    'store_name' => 'Store Name',
                    'discount_amount' => 'Discount Amount',
                    'tax_amount' => 'Tax Amount',
                    'subtotal' => 'Sub Total',
                    'grand_total' => 'Grand Total',
                    'remote_ip' => 'Remote IP',
                ];
                break;
            case 'Invoice':
                $magentoFields = [
                    'entity_id' => 'ID',
                    'state' => 'State',
                    'increment_id' => 'Increment ID',
                    'order_id' => 'Order ID',
                    'created_at' => 'Created At',
                    'updated_at' => 'Updated At',
                    'company' => 'Company',
                    'customer_firstname' => 'Customer First Name',
                    'customer_middlename' => 'Customer Middle Name',
                    'customer_lastname' => 'Customer Last Name',
                    'bill_firstname' => 'Billing First Name',
                    'bill_middlename' => 'Billing Middle Name',
                    'bill_lastname' => 'Billing Last Name',
                    'bill_company' => 'Billing Company',
                    'bill_street' => 'Billing Street',
                    'bill_city' => 'Billing City',
                    'bill_region' => 'Billing State/Province',
                    'bill_postalcode' => 'Billing Zip/Postal Code',
                    'bill_telephone' => 'Billing Telephone',
                    'bill_country_id' => 'Billing Country',
                    'ship_firstname' => 'Shipping First Name',
                    'ship_middlename' => 'Shipping Middle Name',
                    'ship_lastname' => 'Shipping Last Name',
                    'ship_company' => 'Shipping Company',
                    'ship_street' => 'Shipping Street',
                    'ship_city' => 'Shipping City',
                    'ship_region' => 'Shipping State/Province',
                    'ship_postalcode' => 'Shipping Zip/Postal Code',
                    'ship_country_id' => 'Shipping Country',
                    'shipping_amount' => 'Shipping Amount',
                    'order_currency_code' => 'Currency Code',
                    'total_qty' => 'Total Qty',
                    'store_currency_code' => 'Store Currency Code',
                    'discount_description' => 'Discount Description',
                    'shipping_method' => 'Shipping Method',
                    'shipping_incl_tax' => 'Shipping Tax',
                    'discount_amount' => 'Discount Amount',
                    'tax_amount' => 'Tax Amount',
                    'subtotal' => 'Sub Total',
                    'grand_total' => 'Grand Total',
                    'remote_ip' => 'Remote IP',
                ];
                break;
            case 'Campaigns':
                $magentoFields = [
                    'rule_id' => 'Rule ID',
                    
                    'is_active' => 'Active',
                    'discount_amount' => 'Discount Amount',
                ];
                break;
            default:
                break;
        }
        return $magentoFields;
    }

    public function saveMagentoFields($type) {
        $magentoFields = $this->getMagentoFields($type);
        $data = $this->getRequest()->getParams();
        if ($data) {
            $model = Mage::getModel('vtigerintegration/field');
            $defaultData = array(
                'type' => '',
                'vtigerintegration' => '',
                'magento' => serialize($magentoFields),
                'status' => 2,
            );
            foreach ($data as $key => $value) {
                if (isset($defaultData[$key])) {
                    $defaultData[$key] = $value;
                }
            }
            if ($this->getRequest()->getParam('id') != '') {
                $model->setData($defaultData)
                        ->setId($this->getRequest()->getParam('id'));
            } else {
                $model->setData($defaultData);
            }
            $model->save();
            Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('vtigerintegration')->__('Field mapping was successfully saved')
            );
            $this->_redirect('*/*/');
        }
    }

}
