<?php
class Magenest_Vtigerintegration_Block_Adminhtml_Report_Grid extends Mage_Adminhtml_Block_Widget_Grid{
	public function __construct(){
		parent::__construct();
		$this->setId('containerGrid');
		$this->setDefaultSort('id');
		$this->setDefaultDir('ASC');
	}
	protected function _prepareCollection() {
		$collection = Mage::getModel ( 'vtigerintegration/report' )->getCollection();
		$this->setCollection ( $collection );
		return parent::_prepareCollection ();
	}
	public function _prepareColumns(){
		$this->addColumn('id', array(
			'header' => 'ID',
			'align'  => 'right',
			'width'  => '50px',
			'index'  =>	'id'
		));
		$this->addColumn('vtiger_id', array(
			'header' => 'Vtiger Id',
			'align'  => 'left',
			'sortable' => false,
			'type'   => 'text',
			'index'  => 'vtiger_id'
		));
		$this->addColumn('vtiger_table', array(
			'header' => 'Vtiger Table',
			'align'  => 'left',
			'sortable' => false,
			'type'   => 'text',
			'index'  => 'vtiger_table'
		));
		$this->addColumn('date', array(
			'header' => 'Date',
			'align'  => 'left',
			'sortable' => false,
			'type'   => 'text',
			'index'  => 'date'
		));
		$this->addColumn('status', array(
			'header' => 'Status',
			'align'  => 'left',
			'sortable' => false,
			'type'   => 'text',
			'index'  => 'status'
		));
		$this->addColumn ( 'action', array (
				'header' => Mage::helper ( 'vtigerintegration' )->__ ( 'Action' ),
				'align' => 'center',
				'width' => '50px',
				'type' => 'action',
				'getter'     => 'getId',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('vtigerintegration')->__('Edit'),
                        'url'     => array('base'=>'*/*/edit'),
                        'field'   => 'id'
                        )
                    ),
                'filter'    => false,
                'sortable'  => false
		) );
		$this->addExportType ( '*/*/exportCsv', Mage::helper ( 'vtigerintegration' )->__ ( 'CSV' ) );
		$this->addExportType ( '*/*/exportXml', Mage::helper ( 'vtigerintegration' )->__ ( 'Excel XML' ) );
		return parent::_prepareColumns();
	}
	public function getRowUrl($row) {
		return $this->getUrl ( '*/*/edit', array (
				'id' => $row->getId () 
		) );
	}
	protected function _prepareMassaction() {
		$this->setMassactionIdField ( 'id' );
		$this->getMassactionBlock ()->setFormFieldName ( 'id' );
		$this->getMassactionBlock ()->setUseSelectAll ( true );
	
	
		$this->getMassactionBlock ()->addItem ( 'delete', array (
				'label' => Mage::helper ( 'vtigerintegration' )->__ ( 'Delete' ),
				'url'  => $this->getUrl('*/*/massDelete', array('' => '')),        // public function massDeleteAction() in Mage_Adminhtml_Tax_RateController
				'confirm' => Mage::helper('vtigerintegration')->__('Are you sure ?')
		) );
		
		$statuses = array(
			1    => Mage::helper('vtigerintegration')->__('Active'),
			2  => Mage::helper('vtigerintegration')->__('In active')
		);
		array_unshift($statuses, array('label' => '', 'value' => ''));
		$this->getMassactionBlock()->addItem('status', array(
			'label' => Mage::helper('vtigerintegration')->__('Change status'),
			'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
			'additional' => array(
				'visibility' => array(
					'name' => 'status',
					'type' => 'select',
					'class' => 'required-entry',
					'label' => Mage::helper('vtigerintegration')->__('Status'),
					'values' => $statuses
			))
		));
		return $this;
	
	}
}