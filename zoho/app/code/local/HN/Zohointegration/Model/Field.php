<?php
/**
 * 
 */

class HN_Zohointegration_Model_Field extends Mage_Core_Model_Abstract {

	public function _construct() {
		parent::_construct ();
		$this->_init ( 'zohointegration/field' );
	}

	public function setZohoFields($zoho_table)
	{
		$model = Mage::getModel('zohointegration/connector');
		$zoho_fields = $model->getFields($zoho_table);

		return $zoho_fields;
	}

	public function saveFields($zoho_table, $m_table, $update = false)
	{
		$zoho = $this->setZohoFields($zoho_table);
		$data = [
			'type' => $zoho_table,
			'zoho' => $zoho,
			'magento' =>  $m_table,
			'status' => 1
		];
		$id = $this->loadByTable($zoho_table)->getId();
		if($id && $update)
			$this->addData($data);
		else
			$this->setData($data);

		$this->save();

		return $zoho;
	}

	public function getAllTable()
	{
		$table = [
			'Accounts' => 'customer',
			'Contacts' => 'customer',
			'Campaigns' => 'catalogrule',
			'Leads' => 'customer',
			'Products'=> 'product',
			'SalesOrders' => 'order',
			'Invoices' => 'invoice'
		];
		return $table;
	}
	public function changeFields(){
		$table = $this->getAllTable();
		$data = ['' => '--- Select Option ---'];
		foreach ($table as $key => $value) {
				$data[$key] = $key;
		}

		return $data;
	}
	public function getMagentoFields($m_table)
	{
		return $this->setMagentoFields($m_table);
	}

	public function getZohoFields($zoho_table)
	{
		if(!$this->loadByTable($zoho_table)->getId()){
			$alltable = $this->getAllTable();
			$m_table = $alltable[$zoho_table];
			$this->saveFields($zoho_table, $m_table);
		}
			
		$zohoFields = $this->getZoho();
		return unserialize($zohoFields);
	}

	public function loadByTable($zoho_table)
	{
		return $this->load($zoho_table, 'type');
	}
	
	public function setMagentoFields($table)
	{
		$m_fields = [];
		
		switch ($table) {
			case 'customer':
				$m_fields =[
					'entity_id' => 'ID',
					'email' => 'Email',
					'created_at' => 'Created At',
					'update_at' => 'Updated At',
					'is_active' => 'is Active',
					'created_in' => 'Created in',
					'prefix' => 'Prefix',
					'firstname' => 'First name',
					'middlename' => 'Middle Name/Initial',
					'lastname' => 'Last name',
					'taxvat' => 'Tax/VAT Number',
					'gender' => 'Gender',
					'dob' => 'Date of Birth',
					'bill_firstname' => 'Billing First Name',
					'bill_middlename' => 'Billing Middle Name',
					'bill_lastname' => 'Billing Last Name',
					'bill_company' => 'Billing Company',
					'bill_street' => 'Billing Street',
					'bill_city' => 'Billing City',
					'bill_region' => 'Billing State/Province',
					'bill_country_id' => 'Billing Country',
					'bill_postcode' => 'Billing Zip/Postal Code',
					'bill_telephone' => 'Billing Telephone',
					'bill_fax' => 'Billing Fax',
					'ship_firstname' => 'Shipping First Name',
					'ship_middlename' => 'Shipping Middle Name',
					'ship_lastname' => 'Shipping Last Name',					
					'ship_company' => 'Shipping Company',
					'ship_street' => 'Shipping Street',
					'ship_city' => 'Shipping City',
					'ship_region' => 'Shipping State/Province',
					'ship_country_id' => 'Shipping Country',
					'ship_postcode' => 'Shipping Zip/Postal Code',
					'ship_telephone' => 'Shipping Telephone',
					'ship_fax' => 'Shipping Fax',
					'vat_id' => 'VAT number',
				];
				break;

			case 'catalogrule':
				$m_fields = [
					'rule_id' => 'Rule Id',
					'name' => 'Rule Name',
					'description' => 'Description',
					'from_date' => 'From Date',
					'to_date' => 'To Date',
					'is_active' => 'Active',
					'simple_action' => 'Simple Action(Apply)',
					'discount_amount' => 'Discount Amount',
					'sub_is_enable' => 'Enable Discount to Subproducts',
					'sub_simple_action' => 'Subproducts Simple Action(Apply)',
					'sub_discount_amount' => 'Subproducts Discount Amount'
				];
				break;

			case 'product': 
				$m_fields = [
					'name' => 'Name',
					'description' => 'Description',
					'short_description' => 'Short Description',
					'sku' => 'SKU',
					'weight' => 'Weight',
					'news_from_date' => 'Set Product as New from Date',
					'news_to_date' => 'Set Product as New to Date',
					'status' => 'Status',
					'country_of_manufacture' => 'Country of Manufacture',
					'url_key' => 'URL Key',
					'price' => 'Price',
					'special_price' => 'Special Price',
					'special_from_date' => 'Special From Date',
					'special_to_date' => 'Special To Date',
					'stock_stock_id' => 'Stock Id',
					'stock_qty' => 'Qty',
					'meta_title' => 'Meta Title',
					'meta_keyword' => 'Meta Keywords',
					'meta_description' => 'Meta Description',
					'tax_class_id' => 'Tax Class',
					'image' => 'Base Image',
					'small_image' => 'Small Image',
					'thumbnail' => 'Thumbnail',
				];
				break;

			case 'order':
				$m_fields = [
					'entity_id' => 'ID',
					'state' => 'State',
					'status' => 'Status',
					'coupon_code' => 'Coupon Code',
					'coupon_rule_name' => 'Coupon Rule Name',
					'increment_id' => 'Increment ID',
					'created_at' => 'Created At',
					'company' => 'Company',	
					'customer_firstname' => 'Customer First Name',	
					'customer_middlename' => 'Customer Middle Name',
					'customer_lastname'	=> 'Customer Last Name',
					'bill_firstname' => 'Billing First Name',
					'bill_middlename' => 'Billing Middle Name',
					'bill_lastname' => 'Billing Last Name',
					'bill_company' => 'Billing Company',				
					'bill_street' => 'Billing Street',
					'bill_city' => 'Billing City',
					'bill_region' => 'Billing State/Province',
					'bill_postalcode' => 'Billing Zip/Postal Code',
					'bill_telephone' => 'Billing Telephone',
					'bill_country_id' => 'Billing Country',
					'ship_firstname' => 'Shipping First Name',
					'ship_middlename' => 'Shipping Middle Name',
					'ship_lastname' => 'Shipping Last Name',					
					'ship_company' => 'Shipping Company',
					'ship_street' => 'Shipping Street',
					'ship_city' => 'Shipping City',
					'ship_region' => 'Shipping State/Province',
					'ship_postalcode' => 'Shipping Zip/Postal Code',
					'ship_telephone' => 'Shipping Telephone',
					'ship_country_id' => 'Shipping Country',
					'shipping_amount' => 'Shipping Amount',
					'shipping_description' => 'Shipping Description',
					'order_currency_code' => 'Currency Code',
					'total_item_count' => 'Total Item Count',
					'store_currency_code' => 'Store Currency Code',
					'shipping_discount_amount' => 'Shipping Discount Amount',
					'discount_description' => 'Discount Description',
					'shipping_method' => 'Shipping Method',
					'store_name' => 'Store Name',
					'discount_amount' => 'Discount Amount',
					'tax_amount' => 'Tax Amount',								
					'subtotal' => 'Sub Total',
					'grand_total' => 'Grand Total',
					'remote_ip' => 'Remote IP',
				];
				break;
			case 'invoice':
				$m_fields = [
					'entity_id' => 'ID',
					'state' => 'State',
					'increment_id' => 'Increment ID',
					'order_id' => 'Order ID',
					'order_increment_id' => 'Order Increment ID',
					'created_at' => 'Created At',
					'updated_at' => 'Updated At',
					'company' => 'Company',	
					'bill_firstname' => 'Billing First Name',
					'bill_middlename' => 'Billing Middle Name',
					'bill_lastname' => 'Billing Last Name',
					'bill_company' => 'Billing Company',				
					'bill_street' => 'Billing Street',
					'bill_city' => 'Billing City',
					'bill_region' => 'Billing State/Province',
					'bill_postalcode' => 'Billing Zip/Postal Code',
					'bill_telephone' => 'Billing Telephone',
					'bill_country_id' => 'Billing Country',
					'ship_firstname' => 'Shipping First Name',
					'ship_middlename' => 'Shipping Middle Name',
					'ship_lastname' => 'Shipping Last Name',					
					'ship_company' => 'Shipping Company',
					'ship_street' => 'Shipping Street',
					'ship_city' => 'Shipping City',
					'ship_region' => 'Shipping State/Province',
					'ship_postalcode' => 'Shipping Zip/Postal Code',
					'ship_telephone' => 'Shipping Telephone',
					'ship_country_id' => 'Shipping Country',
					'shipping_amount' => 'Shipping Amount',
					'order_currency_code' => 'Currency Code',
					'total_qty' => 'Total Qty',
					'store_currency_code' => 'Store Currency Code',
					'discount_description' => 'Discount Description',
					'shipping_incl_tax' => 'Shipping Include Tax',
					'discount_amount' => 'Discount Amount',
					'tax_amount' => 'Tax Amount',								
					'subtotal' => 'Sub Total',
					'grand_total' => 'Grand Total',
					'remote_ip' => 'Remote IP',
				];

			default:
				break;
		}

		return $m_fields;
	}
}