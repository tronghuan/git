<?php
class HN_Zohointegration_Block_Adminhtml_Report extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		$this->_controller = 'adminhtml_report';
		$this->_blockGroup = 'zohointegration';
		$this->_headerText = Mage::helper('zohointegration')->__('Show Report');
		parent::__construct();
		$this->_removeButton('add');
	}
}
