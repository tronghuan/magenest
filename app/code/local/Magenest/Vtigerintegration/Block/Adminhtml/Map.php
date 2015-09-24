<?php
class Magenest_Vtigerintegration_Block_Adminhtml_Map extends Mage_Adminhtml_Block_Widget_Grid_Container{
	public function __construct(){
		$this->_controller = 'adminhtml_map';
		$this->_blockGroup = 'vtigerintegration';
		$this->_headerText = 'Grid Header';
		$this->_addButtonLabel = 'Add';

		parent::__construct();
	}
}