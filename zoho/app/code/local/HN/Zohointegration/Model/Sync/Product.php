<?php
class HN_Zohointegration_Model_Sync_Product extends HN_Zohointegration_Model_Connector{
	
	public function __construct() {
		parent::__construct();
		$this->_type = 'Products';	
		$this->_table = 'product';
	}

	/**
	 * Update or create new a record
	 *
	 * @param int $id
	 * @param boolean $update
	 * @return string
	 */
	public function sync($id, $update = false) {

		$model = Mage::getModel('catalog/product')->load($id);
		$name = $model->getName();
		$code = $model->getSku();
		$status = $model->getStatus();
			
		$params = $this->_data->getProduct($model, $this->_type);
		$params += [
			'Product Name' => $name,
			'Product Code' =>  $code,
			'Product Active' => $status == 1 ? true : false
		];

		$postXml = '<Products><row no="1">';			
	  	foreach($params as $key => $value)  	
	  		$postXml .= '<FL val="'.$key.'">'.$value.'</FL>';	
		$postXml .= '</row></Products>';

	  	$id = $this->insertRecords($this->_type, $postXml);
	  	
	  	return $id;  
	}

	/**
	 * Delete Record 
	 * @param string $sku
	 */
	public function delete($data){
		$id = $this->searchRecords($this->_type, $data);
		if($id)
			$this->deleteRecords($this->_type, $id);

		return;
	}

}
