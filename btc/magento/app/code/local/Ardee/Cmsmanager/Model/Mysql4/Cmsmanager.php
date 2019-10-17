<?php
class Ardee_Cmsmanager_Model_Mysql4_Cmsmanager extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("cmsmanager/cmsmanager", "cms_id");
    }
}