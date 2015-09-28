<?php
class HN_Zohointegration_Block_Adminhtml_Update extends Mage_Core_Block_Template
{
    protected function getLoadUrl()
    {
        $url =  Mage::helper("adminhtml")->getUrl("zohointegration_admin/adminhtml_retrieve/load");
        
        return $url;
    }

    protected function getUpdateUrl()
    {
        $url =  Mage::helper("adminhtml")->getUrl("zohointegration_admin/adminhtml_retrieve/index");
        
        return $url;
    }
}
