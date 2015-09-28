<?php
class HN_Zohointegration_Block_Adminhtml_Map_Grid extends Mage_Adminhtml_Block_Widget_Grid {
	public function __construct() {
		parent::__construct ();
		$this->setId ( 'mapGrid' );
		$this->setDefaultSort ( 'id' );
		$this->setDefaultDir ( 'ASC' );
		$this->setSaveParametersInSession ( true );
	}
	protected function _prepareCollection() {
		$collection = Mage::getModel ( 'zohointegration/map' )->getResourceCollection();
		$this->setCollection ( $collection );
		return parent::_prepareCollection ();
	}
	protected function _prepareColumns() {
		$this->addColumn ( 'id', [
				'header' => Mage::helper ( 'zohointegration' )->__ ( 'ID' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'id' 
		]);
		
		$this->addColumn ( 'name', [
				'header' => Mage::helper ( 'zohointegration' )->__ ( 'Description' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'name' 
		]);
		
		$this->addColumn ( 'zoho', [
				'header' => Mage::helper ( 'zohointegration' )->__ ( 'ZohoCRM Field' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'zoho' 
		]);
		
		$this->addColumn ( 'magento', [
				'header' => Mage::helper ( 'zohointegration' )->__ ( 'Magento Field' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'magento' 
		]);

		$options = array(
				'0' => __('Inactive') ,
				'1' => __('Active'),
		);
		$this->addColumn ( 'status', [
				'header' => Mage::helper ( 'zohointegration' )->__ ( 'Status' ),
				'align' => 'right',
				'width' => '50px',
				'type' => 'options',
				'options' => $options,
				'index' => 'status' 
		]);
		
		$types = Mage::getSingleton('zohointegration/field')->changeFields();

		$this->addColumn ( 'type', [
				'header' => Mage::helper ( 'zohointegration' )->__ ( 'Type' ),
				'align' => 'right',
				'width' => '50px',
				'type'=> 'options',
				'options'=> $types,
				'index' => 'type' 
		]);
		$this->addColumn ( 'action', [
				'header' => Mage::helper ( 'zohointegration' )->__ ( 'Action' ),
				'align' => 'center',
				'width' => '30px',
				'type' => 'action',
				'getter'     => 'getId',
                'actions'   => [
                    [
                        'caption' => Mage::helper('zohointegration')->__('Edit'),
                        'url'     => ['base'=>'*/*/edit'],
                        'field'   => 'id'
                    ]
                ],
                'filter'    => false,
                'sortable'  => false
		]);
		
		
		$this->addExportType ( '*/*/exportCsv', Mage::helper ( 'zohointegration' )->__ ( 'CSV' ) );
		$this->addExportType ( '*/*/exportXml', Mage::helper ( 'zohointegration' )->__ ( 'Excel XML' ) );
		return parent::_prepareColumns ();
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
				'label' => Mage::helper ( 'zohointegration' )->__ ( 'Delete' ),
				'url'  => $this->getUrl('*/*/massDelete', array('' => '')),        // public function massDeleteAction() in Mage_Adminhtml_Tax_RateController
				'confirm' => Mage::helper('zohointegration')->__('Are you sure ?')
		) );
		
		$statuses = [
			1    => Mage::helper('zohointegration')->__('Active'),
			0  => Mage::helper('zohointegration')->__('In active')
		];
		$this->getMassactionBlock()->addItem('status', array(
			'label' => Mage::helper('zohointegration')->__('Change status'),
			'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
			'additional' => array(
				'visibility' => array(
					'name' => 'status',
					'type' => 'select',
					'class' => 'required-entry',
					'label' => Mage::helper('zohointegration')->__('Status'),
					'values' => $statuses
			))
		));
		return $this;
	
	}
}
