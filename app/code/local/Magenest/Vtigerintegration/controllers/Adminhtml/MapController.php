<?php

/**
 * Map Controller
 * 
 * @author huandt
 */
class Magenest_Vtigerintegration_Adminhtml_MapController extends Mage_Adminhtml_Controller_Action {

    public function indexAction() {
        $this->_title($this->__('Vtiger Integration Mapping Fields'));

        $this->loadLayout()->_setActiveMenu('vtigerintegration/fieldmapping');

        $this->_addContent($this->getLayout()->createBlock('vtigerintegration/adminhtml_map'));

        $this->renderLayout();
    }

    /**
     * Create new Map
     */
    public function newAction() {

        $this->_title($this->__('Vtiger Integration Mapping Fields - Add new'));

        $this->loadLayout();
        $this->_setActiveMenu('vtigerintegration/fieldmapping');
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Vtiger'), Mage::helper('adminhtml')->__('Field Mapping'));

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        if (!$this->getRequest()->getParam('type')) {
            /* Mage_Adminhtml_Helper_Data */
            /* Mage_Adminhtml_Model_Url */
            // Mage::getModel('adminhtml/url')-
            $this->_addContent($this->getLayout()->createBlock('adminhtml/template', 'vtigerintegrationmappingtype', array('template' => 'magenest/vtigerintegration/mappingtype.phtml')));
        } else {
            Mage::register('type', $this->getRequest()->getParam('type'));
            $this->_addContent($this->getLayout()->createBlock('vtigerintegration/adminhtml_map_edit'))
                    ->_addLeft($this->getLayout()->createBlock('vtigerintegration/adminhtml_map_edit_tabs'));
        }
        $this->renderLayout();
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getParams()) {
            $model = Mage::getModel('vtigerintegration/map');
            $defaultData = array(
                'vtigerintegration' => '',
                'magento' => '',
                'status' => 2,
                'type' => '',
                'name' => ''
            );
            foreach ($data as $key => $value) {
                if (isset($defaultData[$key])) {
                    $defaultData[$key] = $value;
                }
            }
            if ($this->getRequest()->getParam('id') != '')
                $model->setData($defaultData)
                        ->setId($this->getRequest()->getParam('id'));
            else
                $model->setData($defaultData);
            $model->save();
            Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('vtigerintegration')->__('Field mapping was successfully saved')
            );
            $this->_redirect('*/*/');
        }
    }

    public function editAction() {
        $this->_title($this->__('Edit'));

        $vtigerId = $this->getRequest()->getParam('id');
        $model = Mage::getModel('vtigerintegration/map')->load($vtigerId);
//        print_r($model->getData()); die();
        if ($model->getId() || $vtigerId == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('magenest_vtigerintegration_mapping', $model);
            $this->loadLayout();
            $this->_setActiveMenu('vtigerintegration/fieldmapping');
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Vtiger'), Mage::helper('adminhtml')->__('Field Mapping'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            if (!$this->getRequest()->getParam('type')) {
                $this->_addContent($this->getLayout()->createBlock('adminhtml/template', 'vtigerintegrationmappingtype', array('template' => 'magenest/vtigerintegration/mappingtype.phtml')));
            } else {
                Mage::register('type', $this->getRequest()->getParam('type'));
                $this->_addContent($this->getLayout()->createBlock('vtigerintegration/adminhtml_map_edit'))
                        ->_addLeft($this->getLayout()->createBlock('vtigerintegration/adminhtml_map_edit_tabs'));
            }
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('vtigerintegration')->__('Rule does not exist')
            );
            $this->_redirect('*/*/');
        }
    }

    public function massDeleteAction() {
        $id = $this->getRequest()->getParam('id');

        if (!is_array($id)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select records.'));
        } else {
            try {
                $model = Mage::getSingleton('vtigerintegration/map');
                foreach ($id as $adId) {
                    $model->load($adId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Total of %d record(s) were deleted.', count($id)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }

     public function massStatusAction() {
     	$staffIds = $this->getRequest()->getParam('id');
     	if (!is_array($staffIds)) {
     		Mage::getSingleton('adminhtml/session')->addError($this->__('Please select staff(s)'));
     	} else {
     		try {
     			foreach ($staffIds as $staffId) {
     				Mage::getSingleton('salesforce/map')
     				->load($staffId)
     				->setStatus($this->getRequest()->getParam('status'))
     				->setIsMassupdate(true)
     				->save();
     			}
     			$this->_getSession()->addSuccess(
     					$this->__('Total of %d record(s) were successfully updated', count($staffIds))
     			);
     		} catch (Exception $e) {
     			$this->_getSession()->addError($e->getMessage());
     		}
     	}
     	$this->_redirect('*/*/');
     }
     public function exportCsvAction() {
     	$fileName = 'salesforce.csv';
     	$content = $this->getLayout()
     	->createBlock('salesforce/adminhtml_map_grid')
     	->getCsv();
     	$this->_prepareDownloadResponse($fileName, $content);
     }
     /**
      * export grid staff to XML type
      */
     public function exportXmlAction() {
     	$fileName = 'salesforce.xml';
     	$content = $this->getLayout()
     	->createBlock('salesforce/adminhtml_map_grid')
     	->getXml();
     	$this->_prepareDownloadResponse($fileName, $content);
     }
}
