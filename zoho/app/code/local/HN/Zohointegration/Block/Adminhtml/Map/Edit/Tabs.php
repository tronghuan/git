<?php
class HN_Zohointegration_Block_Adminhtml_Map_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {
	public function __construct() {
		parent::__construct ();
		$this->setId ( 'lead_map_tabs' );
		$this->setDestElementId ( 'edit_form' );
		$this->setTitle ( Mage::helper ( 'zohointegration' )->__ ( 'Field mapping' ) );
	}
	
	protected function _beforeToHtml() {
	$this->addTab ( 'form_section_general', array (
			'label' => Mage::helper ( 'zohointegration' )->__ ( 'General' ),
			'title' => Mage::helper ( 'zohointegration' )->__ ( 'Field Mapping' ),
			'content' => $this->getLayout ()->createBlock ( 'zohointegration/adminhtml_map_edit_tab_form' )->toHtml ()
	) );
	
	return parent::_beforeToHtml ();
}
}