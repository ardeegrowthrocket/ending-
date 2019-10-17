<?php


class Ardee_Oicdata_Block_Adminhtml_Oicdata extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_oicdata";
	$this->_blockGroup = "oicdata";
	$this->_headerText = Mage::helper("oicdata")->__("Oicdata Manager");
	$this->_addButtonLabel = Mage::helper("oicdata")->__("Add New Item");
	parent::__construct();
	
	}

}