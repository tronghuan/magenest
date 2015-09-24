<?php

class Magenest_Vtigerintegration_Model_Observer {

    /**
     * Configuration pathes use sync data to Vtiger
     */
    const XML_PATH_SYNC_LEAD = 'vtigerintegration/operation/sync_lead';
    const XML_PATH_SYNC_ACCOUNT = 'vtigerintegration/operation/sync_account';
    const XML_PATH_SYNC_CONTACT = 'vtigerintegration/operation/sync_contact';
    const XML_PATH_SYNC_CAMPAIGN = 'vtigerintegration/operation/sync_campaign';
    const XML_PATH_SYNC_PRODUCT = 'vtigerintegration/operation/sync_product';
    const XML_PATH_SYNC_ORDER = 'vtigerintegration/operation/sync_lead';
    const XML_PATH_SYNC_SUBSCRIBER = 'vtigerintegration/operation/sync_subscriber';

    public function syncLead(Varien_Event_Observer $observer) {
        if (!Mage::getStoreConfigFlag(self::XML_PATH_SYNC_LEAD))
            return;
        /* @var $customer Mage_Customer_Model_Customer */
        
        $event = $observer->getCustomer();
        $id = $event->getId();
        $email = $event->getEmail();
        Mage::log($email, null, 'mylog.log', true);
        /* @var $leadSync HN_Salesforce_Model_Leadsync */
        $leadSync = Mage::getModel('vtigerintegration/sync_lead');
        $leadSync->sync($id);
    }

    public function syncOrder(Varien_Event_Observer $observer) {
        if (!Mage::getStoreConfigFlag(self::XML_PATH_SYNC_ORDER))
            return;
        $event = $observer->getEvent()->getOrder();
        $id = $event->getId();
        $sync = Mage::getModel('vtigerintegration/sync_salesorder');
        $sync->sync($id);
    }

    public function syncProduct(Varien_Event_Observer $observer) {

        if (!Mage::getStoreConfigFlag(self::XML_PATH_SYNC_PRODUCT))
            return;

        /* @var $product Mage_Catalog_Model_Product */
        $product = $observer->getProduct();
        $id = $product->getId();
        //Mage::log($product, null, 'mylog.log', true);
        $sync = Mage::getModel('vtigerintegration/sync_product');
        $sync->sync($id, true);
    }

    public function deleteProduct(Varien_Event_Observer $observer) {

        if (!Mage::getStoreConfigFlag(self::XML_PATH_SYNC_PRODUCT))
            return;

        /* @var $product Mage_Catalog_Model_Product */
        $product = $observer->getProduct();
        $sku = $product->getSku();
        $sync = Mage::getModel('vtigerintegration/sync_product');
        $sync->deleteRecordProduct($sku);
    }

    public function updateCustomer(Varien_Event_Observer $observer) {

        $customer = $observer->getCustomerAddress();
        $id = $customer->getCustomerId();

        if (Mage::getStoreConfigFlag(self::XML_PATH_SYNC_LEAD)) {
            $leadsync = Mage::getModel('vtigerintegration/sync_lead');
            $leadsync->sync($id, true);
        }
        if (Mage::getStoreConfigFlag(self::XML_PATH_SYNC_CONTACT)) {
            $contactsync = Mage::getModel('vtigerintegration/sync_contact');
            $contactsync->sync($id, true);
        }
    }

    public function deleteCustomer(Varien_Event_Observer $observer) {

        $customer = $observer->getCustomer();
        $email = $customer->getEmail();
        if (Mage::getStoreConfigFlag(self::XML_PATH_SYNC_LEAD)) {
            $leadsync = Mage::getModel('vtigerintegration/sync_lead');
            $leadsync->sync($email);
        }
        if (Mage::getStoreConfigFlag(self::XML_PATH_SYNC_CONTACT)) {
            $contactsync = Mage::getModel('vtigerintegration/sync_contact');
            $contactsync->sync($email);
        }
    }

    public function syncSubscriber(Varien_Event_Observer $observer) {

        if (!Mage::getStoreConfigFlag(self::XML_PATH_SYNC_SUBSCRIBER))
            return;

        $event = $observer->getEvent();
        $subscriber = $event->getSubscriber();
        $email = $subscriber->getEmail();
        $data = array();

        /* Check login */
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $customerData = Mage::getSingleton('customer/session')->getCustomer();
            $last_name = $customerData->getLastname();
            $data['FirstName'] = $customerData->getFirstname();
        } else {
            $last_name = 'Guest';
        }
        $data['LastName'] = $last_name;
        $data['Email'] = $email;
        $data['Company'] = 'N/A';

        /* Check leads, if not exist create */
        $check = Mage::getModel('vtigerintegration/connector');
        $leadId = $check->searchRecords('Lead', 'Email', $email);
        if ($leadId === false)
            $leadId = $check->createRecords('Lead', $data);
    }

    public function syncCampaign(Varien_Event_Observer $observer) {

        if (!Mage::getStoreConfigFlag(self::XML_PATH_SYNC_CAMPAIGN))
            return;

        $event = $observer->getEvent()->getRule();
        $id = $event->getId();
        $sync = Mage::getModel('vtigerintegration/sync_campaign');
        $sync->sync($id);
    }
    
    public function syncInvoice(Varien_Event_Observer $observer) {

        $event = $observer->getEvent()->getInvoice();
        $id = $event->getId();
        $sync = Mage::getModel('vtigerintegration/sync_invoice')    ;
        $sync->sync($id);
    }

}
