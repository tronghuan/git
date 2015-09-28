<?php
class  HN_Zohointegration_Block_Adminhtml_Map_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {
	
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
		$this->setForm($form);

		$fieldset = $form->addFieldset('general_form', array('legend'=>Mage::helper('zohointegration')->__('Rule')));	
		$mapping = Mage::registry('mapping');
		$model = Mage::getModel('zohointegration/field');
		$magento_value = '';
		$zoho_value = '';
		$name = '';
		$type = '';
		$mageFields = [];
		$zohoFields = [];
		
		/* Pass data to form */
		if($mapping){	
			$type = $mapping->getType();
			$magento_value = $mapping->getMagento();
			$zoho_value = $mapping->getZoho();
			$name = $mapping->getName();
			$table = $model->getAlltable();		
			$zohoFields = $model->getZohoFields($type);
			$mageFields = $model->getMagentoFields($table[$type]);	
		}

		$fields = $model->changeFields();
		$fieldset->addField('type', 'select', 
			[
				'label' => Mage::helper('zohointegration')->__('Select Table'),
				'class' => 'required-entry',
				'required' => true,
				'options' => $fields,
				'name' => 'type',
				'value' => $type,
				'after_element_html' => '<button type="button" id="updateFields">Update Fields</button>' 
			]
		);
	
		$fieldset->addField('magento', 'select', 
			[
				'label' => Mage::helper('zohointegration')->__('Magento field'),
				'class' => 'required-entry',
				'required' => true,
				'options' => $mageFields,
				'name' => 'magento',
				'value' => $magento_value
			]
		);

		$fieldset->addField('zoho', 'select', 
			[
				'label' => Mage::helper('zohointegration')->__('Zoho field'),
				'class' => 'required-entry',
				'required' => true,
				'options' => $zohoFields,
				'name' => 'zoho',
				'value' => $zoho_value
			]
		);
		
		$fieldset->addField('status', 'select', 
			[
				'label' => Mage::helper('zohointegration')->__('Status'),
				'class' => 'required-entry',
				'required' => true,
				'options' => [
					1 => __('Active'),
					0 => __('Inactive') 
				],
				'name' => 'status',
				'value' => 1,
			]
		);
			
		$fieldset->addField('name', 'textarea', array(
				'label' => Mage::helper('zohointegration')->__('Description'),
				'class' => 'required-entry',
				'required' => true,
				'name' => 'name',
				'value' => $name
		));		
	}
}