<?php
class Magenest_Vtigerintegration_Block_Adminhtml_Report extends Mage_Adminhtml_Block_Widget_Grid_Container{
	public function __construct(){
		$this->_controller = 'adminhtml_report';
		$this->_blockGroup = 'vtigerintegration';
		$this->_headerText = 'Grid Header';
		$this->_addButtonLabel = 'Add';

		parent::__construct();
	}
}