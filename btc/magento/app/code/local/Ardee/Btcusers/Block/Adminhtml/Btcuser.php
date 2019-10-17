<?php


class Ardee_Btcusers_Block_Adminhtml_Btcuser extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_btcuser";
	$this->_blockGroup = "btcusers";
	$this->_headerText = Mage::helper("btcusers")->__("Btcuser Manager");
	$this->_addButtonLabel = Mage::helper("btcusers")->__("Add New Item");
	parent::__construct();
	
	}

}