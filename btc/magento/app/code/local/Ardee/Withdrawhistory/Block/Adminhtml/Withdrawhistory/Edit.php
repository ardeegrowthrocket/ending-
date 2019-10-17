<?php
	
class Ardee_Withdrawhistory_Block_Adminhtml_Withdrawhistory_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "btchistoryid";
				$this->_blockGroup = "withdrawhistory";
				$this->_controller = "adminhtml_withdrawhistory";
				$this->_updateButton("save", "label", Mage::helper("withdrawhistory")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("withdrawhistory")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("withdrawhistory")->__("Save And Continue Edit"),
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
				if( Mage::registry("withdrawhistory_data") && Mage::registry("withdrawhistory_data")->getId() ){

				    return Mage::helper("withdrawhistory")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("withdrawhistory_data")->getId()));

				} 
				else{

				     return Mage::helper("withdrawhistory")->__("Add Item");

				}
		}
}