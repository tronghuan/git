<?php
class HN_Zohointegration_Adminhtml_RetrieveController extends Mage_Adminhtml_Controller_Action {
	
	public function indexAction() {
		$model = Mage::getSingleton('zohointegration/field');
		$table = $model->getAllTable();
		foreach ($table as $s_table => $m_table) {
			$model = Mage::getModel('zohointegration/field');
			$model->saveFields($s_table, $m_table, true);
		}
		Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Update Fields success !'));
		$this->_redirect('*/adminhtml_map/index');
	}

	public function loadAction() {

		$params = $this->getRequest()->getParams('type');
		$type = $params['type'];
		if(!$type){
			$out['magento_options'] = "";
			$out['zoho_options'] = "";

			echo json_encode($out);
			return;
		}
		$model = Mage::getSingleton('zohointegration/field');
		$zohoFields = $model->getZohoFields($type);
		$table = $model->getAllTable();
		$mageFields = $model->getMagentoFields($table[$type]);

		$magentoOption = '';
        foreach ($mageFields as $value => $label) {
            $magentoOption .= "<option value='$value' >" . $label . "</option>";
        }
        $out['magento_options'] = $magentoOption;

        $zohoOption = '';        
        foreach ($zohoFields as $value => $label) {
            $zohoOption .=  "<option value='$value' >" . $label . "</option>";
        }   
        $out['zoho_options'] = $zohoOption;

        echo json_encode($out);
        return;
	}

	protected function _isAllowed()
	{
	    return Mage::getSingleton('admin/session')->isAllowed('zohointegration/map');  
	}
}
