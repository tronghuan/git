<?php
class HN_Zohointegration_Block_Adminhtml_Map_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct()
	{
		parent::__construct();
		$this->_objectId = 'map';
		$this->_blockGroup = 'zohointegration';
		$this->_controller = 'adminhtml_map';
		$this->_updateButton('save', 'label', Mage::helper('zohointegration')->__('Save'));
		$this->_updateButton('delete', 'label', Mage::helper('zohointegration')->__('Delete'));
		
	}
	public function getHeaderText()
	{
		if($this->getRequest()->getParam('id')) 
			return Mage::helper('zohointegration')->__("Edit Mapping");
		else 
			return Mage::helper('zohointegration')->__('Add New Mapping');
	}
}
