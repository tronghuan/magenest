<?php
/**
 * Vtigerintegration Report Controller
 * 
 * @author huandt
 */
class Magenest_Vtigerintegration_Adminhtml_ReportController extends Mage_Adminhtml_Controller_Action{
    /**
     * Index Action
     * 
     * @
     */
    public function indexAction(){
        $this->_title($this->__('Vtiger Integration Report'));

        $this->loadLayout()->_setActiveMenu('vtigerintegration/report');

        $this->_addContent($this->getLayout()->createBlock('vtigerintegration/adminhtml_report'));

        $this->renderLayout();
    }
}
