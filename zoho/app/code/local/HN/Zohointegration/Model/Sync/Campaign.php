<?php
class HN_Zohointegration_Model_Sync_Campaign extends HN_Zohointegration_Model_Connector{
		
	public function __construct() {
		parent::__construct();
		$this->_type = 'Campaigns';
		$this->_table = 'catalogrule';			
	}
	
	public function sync($id) {

		$model = Mage::getModel('catalogrule/rule')->load($id);
		$name = $model->getName();	
		$params = $this->_data->getCampaign($model, $this->_type);
		$params += [
			'Campaign Name' => str_replace('%', ' percent', $name)
		];
		$postXml = '<Campaigns><row no="1">';			
		foreach($params as $key => $value)  	
	  		$postXml .= '<FL val="'.$key.'">'.$value.'</FL>';				
		$postXml .= '</row></Campaigns>';

		$id = $this->insertRecords($this->_type, $postXml);

		return $id;
	}
}
 
	
