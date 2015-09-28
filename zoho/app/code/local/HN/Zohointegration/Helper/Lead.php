<?php 
class HN_Zohointegration_Helper_Lead extends HN_Zohointegration_Helper_Data {
	
	/* the array of standart zohocrm of lead  https://www.zoho.com/crm/help/api/modules-fields.html#Leads*/
	protected static $_lead_standard_zoho =array(
	 array('zoho' => 'Last Name', 'required' => 1 ),
	 array('zoho' => "Company"  , 'required' =>1) ,

	array('zoho' => 'Title' , 'magento' => array('type' => "input" )) ,
	array('zoho' => 'Phone' , 'magento' => array('type' => "select" , 'value' => array('Shipping Phone', "Billing Phone"))) ,		
	array('zoho' => 'Mobile' , 'magento' => array('type' => "select" , 'value' => array('Shipping Phone', "Billing Phone"))) ,		
		
	);
	
	public function __construct() {
		
	}
	
}