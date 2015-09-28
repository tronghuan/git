<?php
class HN_Zohointegration_Adminhtml_ReportController extends Mage_Adminhtml_Controller_Action {
	
	public function indexAction() {
		$this->_title($this->__('ZohoCRM Integration Mapping Fields'));
	
		$this->loadLayout ()->_setActiveMenu ( 'zohointegration/fieldmapping' );
	
		$this->_addContent($this->getLayout()->createBlock('zohointegration/adminhtml_report'));
	
		$this->renderLayout ();
	}

	public function massDeleteAction()
	{
		$id = $this->getRequest()->getParam('id');

		if(!is_array($id)) {
			Mage::getSingleton('adminhtml/session')->addError($this->__('Please select records.'));
		} else {
			try {
				$model = Mage::getSingleton('zohointegration/report');
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

	public function exportCsvAction() {
		$fileName = 'sync_report_zoho.csv';
		$content = $this->getLayout()
		->createBlock('zohointegration/adminhtml_report_grid')
		->getCsv();
		$this->_prepareDownloadResponse($fileName, $content);
	}
	
	/**
	 * export grid staff to XML type
	 */
	public function exportXmlAction() {
		$fileName = 'sync_report_zoho.xml';
		$content = $this->getLayout()
		->createBlock('zohointegration/adminhtml_report_grid')
		->getXml();
		$this->_prepareDownloadResponse($fileName, $content);
	}

	protected function _isAllowed()
	{
	    return Mage::getSingleton('admin/session')->isAllowed('zohointegration/report');  
	}
}
