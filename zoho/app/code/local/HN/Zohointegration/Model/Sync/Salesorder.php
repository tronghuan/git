<?php
class HN_Zohointegration_Model_Sync_Salesorder extends HN_Zohointegration_Model_Connector{
	
	public function __construct() {
		parent::__construct();
		$this->_type = 'SalesOrders';
		$this->_table = 'order';
	}

	public function sync($id) {

		$model = Mage::getModel('sales/order')->load($id);
		$customerId = $model->getCustomerId();
		$email = $model->getCustomerEmail();

		/* Sync Account and Contact */
		$account = Mage::getModel('zohointegration/sync_account');
		$contact = Mage::getModel('zohointegration/sync_contact');
		if($customerId){
			$account->sync($customerId);
			$contact->sync($customerId);
		}			
		else{
			$data = [
				'Email' => $email,
				'First Name' => $model->getCustomerFirstname(),
				'Last Name' => $model->getCustomerLastname(),
			];	
			$account->syncByEmail($email);
			$contact->syncByEmail($data);
		}

		$params = Mage::getSingleton('zohointegration/data')->getOrder($model, $this->_type);
 		$params += [
 			'Subject' => $model->getIncrementId(),
 			'Account Name' => $email,
 			'Status' => 'Created',
 		];

 		$postXml = '<SalesOrders><row no="1">';			
	  	
	  	foreach($params as $key => $value)  	
	  		$postXml .= '<FL val="'.$key.'">'.$value.'</FL>';

	  	$postXml .= $this->addProduct($model);	
		$postXml .= '</row></SalesOrders>';
		
		$id = $this->insertRecords($this->_type , $postXml);

		return $id;
	}

	public function addProduct($model){

		$i = 1;
		$productDetailXml ='<FL val="Product Details">';
		foreach ($model->getAllItems() as $item) {

			/* @var $item Mage_Sales_Model_Order_salesorder_Item */
			$productId= $item->getProductId();

			$name = Mage::getModel('catalog/product')->load($productId)->getName();
			$sku= $item->getSku();
			$price = $item->getPrice();
			$qty = $item->getQtyOrdered();
			$tax = $item->getTaxAmount();
			$total = $item->getRowTotal();
			$discount = $item->getDiscountAmount();

			if($price == 0)
				continue;
			
			$id = Mage::getModel('zohointegration/sync_product')->sync($productId);
			$productDetailXml .= '<product no="'.$i.'">';
			$productDetailXml .= '<FL val="Product Id">'.$id.'</FL>';
			$productDetailXml .= '<FL val="Product Name">'.$name.'</FL>';
			$productDetailXml .= '<FL val="Quantity">'.$qty.'</FL>';
			$productDetailXml .= '<FL val="List Price">'.$price.'</FL>';
			$productDetailXml .= '<FL val="Unit Price">'.$price.'</FL>';
			$productDetailXml .= '<FL val="Total">'.$total.'</FL>';
			$productDetailXml .= '<FL val="Discount">'.$discount.'</FL>';

			$total_after_discount = $total - $discount;
			$net_total = $total_after_discount + $tax;

			$productDetailXml .= '<FL val="Total After Discount">'.$total_after_discount.'</FL>';
			$productDetailXml .= '<FL val="Tax">'.$tax.'</FL>';
			$productDetailXml .= '<FL val="Net Total">'.$net_total.'</FL>';
			$productDetailXml .= '</product>';
			$i++;
		}
		$productDetailXml .= '</FL>';
		
		$productDetailXml .='<FL val="Sub Total">'.$model->getSubtotal().'</FL>';
		$productDetailXml .='<FL val="Discount">'.$model->getDiscountAmount().'</FL>';
		$productDetailXml .='<FL val="Tax">'.$model->getTaxAmount().'</FL>';
		$productDetailXml .='<FL val="Adjustment">'.$model->getShippingAmount().'</FL>';
		$productDetailXml .='<FL val="Grand Total">'.$model->getGrandTotal().'</FL>';
		
		return $productDetailXml;
	}
}