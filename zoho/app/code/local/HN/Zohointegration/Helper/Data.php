<?php
class HN_Zohointegration_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function sendCurlRequest( $methodname, $param) {
		
		$authkey = get_option('zoho_crm_api_auth_key');
	
		
	
		try {
			$url = $this->target_url ."/".$methodname;
			$parameter = 'scope=crmapi';
			$parameter.= "&authtoken={$authkey}";//Give your authtoken
			$parameter .= "&xmlData={$param}";
			/* initialize curl handle */
			$ch = curl_init ();
			/* set url to send post request */
			curl_setopt ( $ch, CURLOPT_URL, $url );
			/* allow redirects */
			curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
			/* return a response into a variable */
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
			/* times out after 300s */
			curl_setopt ( $ch, CURLOPT_TIMEOUT, 300 );
			/* set POST method */
			curl_setopt ( $ch, CURLOPT_POST, 1 );
			/* add POST fields parameters */
			curl_setopt ( $ch, CURLOPT_POSTFIELDS, $parameter );
			/* execute the cURL */
			$result = curl_exec ( $ch );
			curl_close ( $ch );
				
			if (strpos($result, 'xml') > -1) {
				$xmlParse = simplexml_load_string($result);
	
				if ($xmlParse->result->recorddetail) {
					$fl = $xmlParse->result->recorddetail->FL[0];
						
					/** @var $fl SimpleXMLElement */
						
					$id_of_zoho =(string) $fl;
					$zoho_mess = (string)$xmlParse->result->message;
					$this->insert_report($this->woo_side_id, $id_of_zoho, $this->log_type);
				}
			}
			return $result;
		} catch ( Exception $exception ) {
			echo 'Exception Message: ' . $exception->getMessage () . '<br/>';
			echo 'Exception Trace: ' . $exception->getTraceAsString ();
		}
	}
	
}