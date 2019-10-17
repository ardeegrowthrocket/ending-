<?php
class Ardee_Withdrawhistory_Model_Mysql4_Withdrawhistory extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("withdrawhistory/withdrawhistory", "btchistoryid");
    }
}