<?php
class HN_Zohointegration_Block_Adminhtml_Map_Map extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		$this->_controller = 'adminhtml_map';
		$this->_blockGroup = 'zohointegration';
		$this->_headerText = Mage::helper('zohointegration')->__('Fields mapping management');
		$this->_addButtonLabel = Mage::helper('zohointegration')->__('Add');

		parent::__construct();
	}
}
