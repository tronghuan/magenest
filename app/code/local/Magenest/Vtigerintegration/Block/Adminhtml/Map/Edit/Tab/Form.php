<?php

class Magenest_Vtigerintegration_Block_Adminhtml_Map_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
//		$form = new Varien_Data_Form(array("encrypt","multipart/form-data"));
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset('general_form', array('legend' => Mage::helper('vtigerintegration')->__('Rule')));




        $type = Mage::registry('type');
        $model_class = 'vtigerintegration/sync_' . $type;
        $type_model = Mage::getModel($model_class);
        $vtigerFields = $type_model->getVtigerFields();
        $magentoFields = $type_model->getMagentoFields($type);
        $fieldset->addField('type', 'hidden', array(
            'class' => 'hidden',
            'name' => 'type'
        ));
        $fieldset->addField('vtigerintegration', 'select', array(
            'label' => Mage::helper('vtigerintegration')->__('Vtiger Field'),
            'class' => 'required-entry',
            'required' => true,
            'options' => $vtigerFields,
            'name' => 'vtigerintegration',
        ));

        $fieldset->addField('magento', 'select', array(
            'label' => Mage::helper('vtigerintegration')->__('Magento field'),
            'class' => 'required-entry',
            'required' => true,
            'options' => $magentoFields,
            'name' => 'magento',
        ));


        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('vtigerintegration')->__('Status field'),
            'class' => 'required-entry',
            'required' => true,
            'options' =>array(
					'1' => __('Active'),
						'2' => __('In active') 
				),
            'name' => 'status',
        ));

        $fieldset->addField('name', 'text', array(
            'label' => Mage::helper('vtigerintegration')->__('Description'),
            'name' => 'name',
        ));

        //set edit form value

        if ($edit_data = Mage::registry('edit_data') && !empty($edit_data)) {
            $edit_data['type'] = $type;
        } else {
            $edit_data = array();
            $edit_data['type'] = $type;
        }

        $form->setValues($edit_data);
    }

}
