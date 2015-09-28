<?php
class HN_Zohointegration_Model_Connector {
	
	const XML_PATH_ZOHO_CONFIG_EMAIL = 'zohointegration/auth/user_id';
	const XML_PATH_ZOHO_CONFIG_PASSWD = 'zohointegration/auth/password';
	const XML_PATH_ZOHO_CONFIG_AUTHTOKEN = 'zohointegration/auth/auth_key';

	protected $_type;
	protected $_data;

	public function __construct() {
		$this->base_url = "https://crm.zoho.com/crm/private/xml/";
		$this->_data = Mage::getSingleton('zohointegration/data');
	}

	/**
	 * Get AUTHTOKEN
	 *
	 * @return string
	 */
	public function getAuth(){

		$authkey = Mage::getStoreConfig(self::XML_PATH_ZOHO_CONFIG_AUTHTOKEN);		
		if (!$authkey) {
		 	$email =  Mage::getStoreConfig(self::XML_PATH_ZOHO_CONFIG_EMAIL); //get User from config
		 	$password = Mage::getStoreConfig(self::XML_PATH_ZOHO_CONFIG_PASSWD); //get Password from config
		 	
		 	$url = "https://accounts.zoho.com/apiauthtoken/nb/create";
			$paramter = "SCOPE=ZohoCRM/crmapi&EMAIL_ID=".$email."&PASSWORD=".$password;

			$adapter = new Zend_Http_Client_Adapter_Curl();
			$client = new Zend_Http_Client($url);
			$client->setAdapter($adapter);
			$adapter->setConfig(array(
	      				CURLOPT_HEADER => false,
	      				CURLOPT_RETURNTRANSFER => true
			));
			if($paramter)
				$client->setRawData($paramter);
	    	$response = $client->request('POST')->getBody();
			$anArray = explode("\n",$response);
			$authToken = explode("=",$anArray['2']);
			
			$authkey = $authToken['1'];
			$config = Mage::getModel('core/config');
			$config->saveConfig('zohointegration/auth/auth_key', $authkey, 'default', 0); //save authenkey to config			
		}

		return $authkey;
	}

    /**
	 * Request to Server
	 *
	 * @param string $path
	 * @param string $paramter
	 * @return array
	 */
	public function _sendRequest($path, $paramter = null , $method = Zend_Http_Client::GET)
    {
    	$authtoken = $this->getAuth();
    	$url = $this->base_url.$path;
    	$params = "authtoken=".$authtoken."&scope=crmapi";
    	$paramter = $params.$paramter;

    	$adapter = new Zend_Http_Client_Adapter_Curl();
		$client = new Zend_Http_Client($url);
		$client->setAdapter($adapter);
		$client->setConfig([
			'timeout' => 300
		]);
		if($paramter)
			$client->setRawData($paramter);
    	$response = $client->request($method)->getBody();

		/* Convert result format XML from Zoho to array */
		$parser = xml_parser_create();
		xml_parse_into_struct($parser, $response, $result, $index);
		xml_parser_free($parser);
		try{

			if($result[2]['tag'] == 'CODE'){
				throw new Exception("Can't sync data to ZohoCRM, because invalid AUTHTOKEN ZohoCRM, please check AUTHTOKEN again !");
				exit;
			}
		}catch(Exception $exception){
			Mage::getSingleton('adminhtml/session')->addError(
					Mage::helper('zohointegration')->__($exception->getMessage())
			);
		}

    	return $result;
    }
    
    /**
	 * Get fields from Zoho
	 *
	 * @return string
	 */
	public function getFields($table){
		
		$path = $table. DS ."getFields";
		$result = $this->_sendRequest($path, null, 'POST');
		$field_zoho = [];
		$i=0;
		$type_array = [
			'Multiselect Pick List',
			'Lookup',
			'Pick List',
			'OwnerLookup',
		];
		foreach($result as $key => $value){
 			if($value['tag'] == 'FL' && !empty($value['attributes']['LABEL'])){
 				$type = $value['attributes']['TYPE'];
 				if(!in_array($type, $type_array)){
 					$label = $value['attributes']['LABEL'];
	 				$field_zoho[$label] = $label.' ('.$type.')';//save Zoho Field to array
 				}
 			}
		}

		return serialize($field_zoho);
	}

    /**
	 * Create new Record in Zoho
	 *
	 * @param string $table
	 * @param string $postXML
	 * @return string or false
	 */
	public function insertRecords($table, $postXML) {

		$path = $table. DS .'insertRecords';
		$paramter = "&duplicateCheck=2&newFormat=1&vesion=2&xmlData=$postXML";
		$response = $this->_sendRequest($path, $paramter, Zend_Http_Client::POST);
		$action = '';
		if($response[2]['tag'] == 'MESSAGE'){
			$sub = substr($response[2]['value'], 10, 5);
			if($sub == 'added')
				$action = "Create";
			else
				$action = "Update";
		}
		if($response[4]['tag'] != 'ERROR'){
			$id = $response[4]['value'];
			$this->saveReport($id, $action, $table);

			return $id;
		}				

		return false;
	}

	/**
	 * Update Record in Zoho
	 *
	 * @param string $table
	 * @param string $postXML
	 * @return string or false
	 */
	public function updateRecords($table, $id, $postXML) {

		$path = $table. DS .'updateRecords';
		$paramter = "&id=$id&newFormat=1&xmlData={$postXML}";
		$response = $this->_sendRequest($path, $paramter, Zend_Http_Client::POST);
		return;
	}

	/**
	 * Delete a Record in Zoho
	 *
	 * @param string $table
	 * @param string $postXML
	 * @return string or false
	 */
	public function deleteRecords($table, $id) {

		$path = $table. DS .'deleteRecords';
		$paramter = "&id=$id";
		$response = $this->_sendRequest($path, $paramter, Zend_Http_Client::POST);
		$this->saveReport($id, 'Delete', $table);
		return;
	}

	/**
	 * Search recordId in Zoho
	 *
	 * @param string $table
	 * @param string or array $data
	 * @return string or false
	 */	
	public function searchRecords($table, $data){

		$path = $table. DS ."searchRecords";
		$params = "&criteria=";
		if($table == 'Products')			
			$params .=  "((Product Name:".$data['Product Name'].")AND(Product Code:".$data['Product Code']."))";
		elseif($table == 'Accounts')			
			$params .=  "(Account Name:$data)";
		else
			$params .= "(Email:$data)";		
		$response = $this->_sendRequest($path, $params, 'POST');
		if($response[1]['tag']=='RESULT'){
			$id = $response[4]['value'];
			return $id;
		}			
		else
			return false;
	}

	public function saveReport($id, $action, $table){
		$model = Mage::getSingleton('zohointegration/report');
		$model->saveReport($id, $action, $table);
		return;
	}
}
