<?php
class Ardee_Btcusers_Model_Mysql4_Btcuser extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("btcusers/btcuser", "btcid");
    }
}