<?php
class HN_Zohointegration_Block_Adminhtml_Report_Grid extends Mage_Adminhtml_Block_Widget_Grid {
	public function __construct() {
		parent::__construct ();
		$this->setId ( 'reportGrid' );
		$this->setDefaultSort ( 'id' );
		$this->setDefaultDir ( 'ASC' );
		$this->setSaveParametersInSession ( true );
	}
	protected function _prepareCollection() {
		$collection = Mage::getModel ( 'zohointegration/report' )->getResourceCollection();
		$this->setCollection ( $collection );
		return parent::_prepareCollection ();
	}
	protected function _prepareColumns() {
		$this->addColumn ( 'id', array (
				'header' => Mage::helper ( 'zohointegration' )->__ ( 'ID' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'id' 
		) );

		$this->addColumn ( 'record_id', array (
				'header' => Mage::helper ( 'zohointegration' )->__ ( 'Record ID in Zoho' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'record_id' 
		) );

		$action = [
				'create' => __('Create') ,
				'update' => __('Update'),
				'delete' => __('Delete'),
		];

		$this->addColumn ( 'action', array (
				'header' => Mage::helper ( 'zohointegration' )->__ ( 'Action' ),
				'align' => 'right',
				'width' => '50px',
				'type'=> 'options',
				'options' => $action,
				'index' => 'action' 
		) );
		
		$tables = Mage::getSingleton('zohointegration/field')->changeFields();
		$this->addColumn ( 'table', array (
				'header' => Mage::helper ( 'zohointegration' )->__ ( 'Zoho Table' ),
				'align' => 'right',
				'width' => '50px',
				'type'=> 'options',
				'options' => $tables,
				'index' => 'table' 
		) );
		
		$this->addColumn ( 'username', array (
				'header' => Mage::helper ( 'zohointegration' )->__ ( 'Username' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'username' 
		) );

		$this->addColumn ( 'email', array (
				'header' => Mage::helper ( 'zohointegration' )->__ ( 'Email' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'email' 
		) );

		$this->addColumn ( 'datetime', array (
				'header' => Mage::helper ( 'zohointegration' )->__ ( 'Date time' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'datetime' 
		) );
		
		$this->addExportType ( '*/*/exportCsv', Mage::helper ( 'zohointegration' )->__ ( 'CSV' ) );
		$this->addExportType ( '*/*/exportXml', Mage::helper ( 'zohointegration' )->__ ( 'Excel XML' ) );
		return parent::_prepareColumns ();
	}

	protected function _prepareMassaction() {
		$this->setMassactionIdField ( 'id' );
		$this->getMassactionBlock ()->setFormFieldName ( 'id' );
		$this->getMassactionBlock ()->setUseSelectAll ( true );	
	
		$this->getMassactionBlock ()->addItem ( 'delete', array (
				'label' => Mage::helper ( 'zohointegration' )->__ ( 'Delete' ),
				'url'  => $this->getUrl('*/*/massDelete', array('' => '')),        // public function massDeleteAction() in Mage_Adminhtml_Tax_RateController
				'confirm' => Mage::helper('zohointegration')->__('Are you sure ?')
		) );
		return $this;
	
	}
}
