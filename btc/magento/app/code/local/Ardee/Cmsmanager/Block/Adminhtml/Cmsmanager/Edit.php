<?php
	
class Ardee_Cmsmanager_Block_Adminhtml_Cmsmanager_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "cms_id";
				$this->_blockGroup = "cmsmanager";
				$this->_controller = "adminhtml_cmsmanager";
				$this->_updateButton("save", "label", Mage::helper("cmsmanager")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("cmsmanager")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("cmsmanager")->__("Save And Continue Edit"),
					"onclick"   => "saveAndContinueEdit()",
					"class"     => "save",
				), -100);



				$this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
		}

		public function getHeaderText()
		{
				if( Mage::registry("cmsmanager_data") && Mage::registry("cmsmanager_data")->getId() ){

				    return Mage::helper("cmsmanager")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("cmsmanager_data")->getId()));

				} 
				else{

				     return Mage::helper("cmsmanager")->__("Add Item");

				}
		}
}