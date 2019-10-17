<?php
class Ardee_Oicdata_Model_Mysql4_Oicdata extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("oicdata/oicdata", "oic_id");
    }
}