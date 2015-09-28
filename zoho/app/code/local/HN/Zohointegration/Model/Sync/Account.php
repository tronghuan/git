<?php
class HN_Zohointegration_Model_Sync_Account extends HN_Zohointegration_Model_Connector{
	
	public function __construct() {
		parent::__construct();
		$this->_type = 'Accounts';	
		$this->_table = 'customer';			
	}

	/**
	 * Update or create new a record
	 *
	 * @param int $id
	 * @param boolean $update
	 * @return string
	 */
	public function sync($id, $update = false) {

		$model = Mage::getSingleton('customer/customer')->load($id);
		$email = $model->getEmail();
		$params = $this->_data->getCustomer($model, $this->_type);
		$params += [
			'Account Name' => $email
		];

		/* Format XML send data */	  	
	  	$postXml = '<Accounts><row no="1">';	  	
	  	foreach($params as $key => $value)  	
	  		$postXml .= '<FL val="'.$key.'">'.$value.'</FL>';		
	  	$postXml .= '</row></Accounts>'; 
  		$id = $this->insertRecords($this->_type, $postXml);

	  	return $id;  	
	}

	public function syncByEmail($email) {

		$params = ['Account Name' => $email];

		/* Format XML send data */	  	
	  	$postXml = '<Accounts><row no="1">';	  	
	  	foreach($params as $key => $value)  	
	  		$postXml .= '<FL val="'.$key.'">'.$value.'</FL>';		
	  	$postXml .= '</row></Accounts>'; 
  		$id = $this->insertRecords($this->_type, $postXml);

	  	return $id;  	
	}

	/**
	 * Delete Record 
	 * @param string email
	 */
	public function delete($email){
		$id = $this->searchRecords($this->_type, $email);
		if($id)
			$this->deleteRecords($this->_type, $id);

		return;
	}
	
}
