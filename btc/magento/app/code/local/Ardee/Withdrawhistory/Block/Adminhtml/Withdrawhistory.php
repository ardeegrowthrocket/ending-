<?php


class Ardee_Withdrawhistory_Block_Adminhtml_Withdrawhistory extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_withdrawhistory";
	$this->_blockGroup = "withdrawhistory";
	$this->_headerText = Mage::helper("withdrawhistory")->__("Withdrawhistory Manager");
	$this->_addButtonLabel = Mage::helper("withdrawhistory")->__("Add New Item");
	parent::__construct();
	
	}

}