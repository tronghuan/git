<?php
class HN_Zohointegration_Model_Resource_Field extends Mage_Core_Model_Resource_Db_Abstract
{
	public function _construct()
	{
		$this->_init('zohointegration/field', 'id');
	}
}