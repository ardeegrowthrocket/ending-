<?php
	
class Ardee_Btcusers_Block_Adminhtml_Btcuser_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "btcid";
				$this->_blockGroup = "btcusers";
				$this->_controller = "adminhtml_btcuser";
				$this->_updateButton("save", "label", Mage::helper("btcusers")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("btcusers")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("btcusers")->__("Save And Continue Edit"),
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
				if( Mage::registry("btcuser_data") && Mage::registry("btcuser_data")->getId() ){

				    return Mage::helper("btcusers")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("btcuser_data")->getId()));

				} 
				else{

				     return Mage::helper("btcusers")->__("Add Item");

				}
		}
}