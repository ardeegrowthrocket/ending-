<?php


class Ardee_Cmsmanager_Block_Adminhtml_Cmsmanager extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_cmsmanager";
	$this->_blockGroup = "cmsmanager";
	$this->_headerText = Mage::helper("cmsmanager")->__("Cmsmanager Manager");
	$this->_addButtonLabel = Mage::helper("cmsmanager")->__("Add New Item");
	parent::__construct();
	
	}

}