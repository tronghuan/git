<?php
class HN_Zohointegration_Adminhtml_MapController extends Mage_Adminhtml_Controller_Action {

	public function indexAction() {
		$this->_title($this->__('ZohoCRM Integration Mapping Fields'));
	
		$this->loadLayout ()->_setActiveMenu ( 'zohointegration/fieldmapping' );
	
		$this->_addContent($this->getLayout()->createBlock('zohointegration/adminhtml_map'));
	
		$this->renderLayout ();
	}
	
	public function newAction()
	{
		$this->_title($this->__('ZohoCRM Integration - Add new mapping'));
	
		$this->loadLayout();
		$this->_setActiveMenu('zohointegration/fieldmapping');
		$this->_addBreadcrumb(Mage::helper('adminhtml')->__('ZohoCRM'), Mage::helper('adminhtml')->__('Field Mapping'));
	
		$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
		
		$this->_addContent ( $this->getLayout ()->createBlock ( 'zohointegration/adminhtml_map_edit' ) )
				->_addLeft ( $this->getLayout ()->createBlock ( 'zohointegration/adminhtml_map_edit_tabs' ) );

		$this->renderLayout();
	}
	
	public function saveAction() {

		if ($data = $this->getRequest()->getParams()) {				
			$model = Mage::getModel('zohointegration/map');
			$defaultData = array(
					'name'=> '',
					'zoho' => '',
					'magento' =>'',
					'status' => 1,
					'type'=>''
			);				
			foreach ($data as $key => $value) {
				if (isset($defaultData[$key])) {
					$defaultData[$key] = $value;
				}
			}
				
			if($this->getRequest()->getParam('id') != '')	
				$model->setData($defaultData)
		            		->setId($this->getRequest()->getParam('id'));
			else
				$model->addData($defaultData);
			$model->save();
				
			Mage::getSingleton('adminhtml/session')->addSuccess(
					Mage::helper('zohointegration')->__('Field mapping was successfully saved')
			);
			$this->_redirect('*/*/');
		}
	}
    public function editAction() 
    {
    	$this->_title($this->__('Edit'));
    	$id = $this->getRequest()->getParam('id');
    	$model = Mage::getModel('zohointegration/map')->load($id);
    	
    	if ($model->getId() || $id != 0) {
    		$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
    		if (!empty($data)) {
    			$model->setData($data);
    		} 
    		Mage::register('mapping', $model);
	    	$this->loadLayout();
	    	$this->_setActiveMenu('zohointegration/fieldmapping');
	    	$this->_addBreadcrumb(Mage::helper('adminhtml')->__('ZohoCRM'), Mage::helper('adminhtml')->__('Field Mapping'));
	    	
	    	$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);	    
	    	$this->_addContent ( $this->getLayout ()->createBlock ( 'zohointegration/adminhtml_map_edit' ) )
	    		->_addLeft ( $this->getLayout ()->createBlock ( 'zohointegration/adminhtml_map_edit_tabs' ) );
	    		
	    	$this->renderLayout();
    	} else {
    		Mage::getSingleton('adminhtml/session')->addError(
    				Mage::helper('zohointegration')->__('Rule does not exist')
    		);
    		$this->_redirect('*/*/');
    	}
    }
	
	
	public function massDeleteAction()
	{
		$id= $this->getRequest()->getParam('id');

		if(!is_array($id)) {
			Mage::getSingleton('adminhtml/session')->addError($this->__('Please select records.'));
		} else {
			try {
				$model = Mage::getSingleton('zohointegration/map');
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
					Mage::getSingleton('zohointegration/map')
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
		$fileName = 'mapping_zoho.csv';
		$content = $this->getLayout()
		->createBlock('zohointegration/adminhtml_map_grid')
		->getCsv();
		$this->_prepareDownloadResponse($fileName, $content);
	}
	
	/**
	 * export grid staff to XML type
	 */
	public function exportXmlAction() {
		$fileName = 'mapping_zoho.xml';
		$content = $this->getLayout()
		->createBlock('zohointegration/adminhtml_map_grid')
		->getXml();
		$this->_prepareDownloadResponse($fileName, $content);
	}

	protected function _isAllowed()
	{
	    return Mage::getSingleton('admin/session')->isAllowed('zohointegration/map');  
	}
	
}