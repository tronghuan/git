<?php
class HN_Zohointegration_Model_Observer {

	/**#@+
     * Configuration pathes use sync data to Zoho
     */
	const XML_PATH_SYNC_LEAD = 'zohointegration/sync/lead';

	const XML_PATH_SYNC_ACCOUNT = 'zohointegration/sync/account';

	const XML_PATH_SYNC_CONTACT = 'zohointegration/sync/contact';

	const XML_PATH_SYNC_CAMPAIGN = 'zohointegration/sync/campaign';

	const XML_PATH_SYNC_PRODUCT = 'zohointegration/sync/product';	

	const XML_PATH_SYNC_ORDER = 'zohointegration/sync/lead';

	const XML_PATH_SYNC_INVOICE = 'zohointegration/sync/invoice';

	const XML_PATH_SYNC_SUBSCRIBER = 'zohointegration/sync/subscriber';

	public function __construct()
	{
		$this->_lead = Mage::getModel('zohointegration/sync_lead');
		$this->_contact = Mage::getModel('zohointegration/sync_contact');
		$this->_campaign = Mage::getModel('zohointegration/sync_campaign');
		$this->_product = Mage::getModel('zohointegration/sync_product');
		$this->_order = Mage::getModel('zohointegration/sync_salesorder');
		$this->_invoice = Mage::getModel('zohointegration/sync_invoice');
	}

	public function syncLead(Varien_Event_Observer $observer) 
	{
		/* @var $customer Mage_Customer_Model_Customer */
		$event = $observer->getCustomer();
		$id = $event->getId();
		if(Mage::getStoreConfigFlag(self::XML_PATH_SYNC_LEAD))
			$this->_lead->sync($id);
	}

	public function updateCustomer(Varien_Event_Observer $observer) 
	{			
		$customer = $observer->getCustomerAddress();
		$id = $customer->getCustomerId();

		if(Mage::getStoreConfigFlag(self::XML_PATH_SYNC_LEAD))
			$this->_lead->sync($id, true);

		if(Mage::getStoreConfigFlag(self::XML_PATH_SYNC_CONTACT))
			$this->_contact->sync($id, true);
	}

	public function deleteCustomer(Varien_Event_Observer $observer) 
	{
		$customer = $observer->getCustomer();
		$email = $customer->getEmail();
		if(Mage::getStoreConfigFlag(self::XML_PATH_SYNC_LEAD))
			$this->_lead->delete($email);

		if(Mage::getStoreConfigFlag(self::XML_PATH_SYNC_CONTACT))
			$this->_contact->delete($email);
	}	

	public function syncCampaign(Varien_Event_Observer $observer)
	{
		if(!Mage::getStoreConfigFlag(self::XML_PATH_SYNC_CAMPAIGN))
			return;

		$event = $observer->getEvent()->getRule();
		$id = $event->getId();
		$this->_campaign->sync($id);
	}

	public function syncOrder(Varien_Event_Observer $observer) 
	{
		if(!Mage::getStoreConfigFlag(self::XML_PATH_SYNC_ORDER))
			return;
		$event = $observer->getEvent()->getOrder();
		$id= $event->getId();	
		$this->_order->sync($id);
	}

	public function syncProduct(Varien_Event_Observer $observer) {
		
		/* @var $product Mage_Catalog_Model_Product */
		$product = $observer->getProduct();
		$id = $product->getId();
		if(Mage::getStoreConfigFlag(self::XML_PATH_SYNC_PRODUCT))
			$this->_product->sync($id, true);		
	}

	public function deleteProduct(Varien_Event_Observer $observer) {

		/* @var $product Mage_Catalog_Model_Product */
		$product = $observer->getProduct();
		$sku = $product->getSku();
		$name = $product->getName();
		$data = [
			'Product Name' => $name,
			'Product Code' => $sku
		];

		if(Mage::getStoreConfigFlag(self::XML_PATH_SYNC_PRODUCT))
			$this->_product->delete($data);
	}
	
	public function syncSubscriber(Varien_Event_Observer $observer){

		if(!Mage::getStoreConfigFlag(self::XML_PATH_SYNC_SUBSCRIBER))
			return;

		$event = $observer->getEvent();
		$subscriber = $event->getSubscriber();
		$email = $subscriber->getEmail();		
		$data = [];

		/* Check login */
		if(Mage::getSingleton('customer/session')->isLoggedIn()) {
			$customerData = Mage::getSingleton('customer/session')->getCustomer();
			$last_name= $customerData->getLastname();
			$data['First Name']= $customerData->getFirstname();
		}
		else{
			$last_name = 'Guest';
		} 
		$data['Last Name'] = $last_name;
		$data['Email'] = $email ;
		
		$this->_lead->syncByEmail($data);
	}

	public function syncInvoice(Varien_Event_Observer $observer) 
	{
		if(!Mage::getStoreConfigFlag(self::XML_PATH_SYNC_INVOICE))
			return;

		$event = $observer->getInvoice();
		$id = $event->getId();
		$this->_invoice->sync($id);
	}
}