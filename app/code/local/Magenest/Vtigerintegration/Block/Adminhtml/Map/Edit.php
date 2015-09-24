<?php
class Magenest_Vtigerintegration_Block_Adminhtml_Map_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct()
	{
		parent::__construct();
		$this->_objectId = 'map';
		$this->_blockGroup = 'vtigerintegration';
		$this->_controller = 'adminhtml_map';
		$this->_updateButton('save', 'label', Mage::helper('vtigerintegration')->__('Save'));
		$this->_updateButton('delete', 'label', Mage::helper('vtigerintegration')->__('Delete'));
		
	}
	public function getHeaderText()
	{

	if( $this->getRequest()->getParam('id') != '' ) {
		return Mage::helper('vtigerintegration')->__("Edit Rule");
	}	
	else {
		return Mage::helper('vtigerintegration')->__('Add');
	}
	}
}
