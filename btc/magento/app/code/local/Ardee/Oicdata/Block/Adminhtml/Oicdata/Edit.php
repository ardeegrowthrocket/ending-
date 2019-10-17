<?php
	
class Ardee_Oicdata_Block_Adminhtml_Oicdata_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "oic_id";
				$this->_blockGroup = "oicdata";
				$this->_controller = "adminhtml_oicdata";
				$this->_updateButton("save", "label", Mage::helper("oicdata")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("oicdata")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("oicdata")->__("Save And Continue Edit"),
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
				if( Mage::registry("oicdata_data") && Mage::registry("oicdata_data")->getId() ){

				    return Mage::helper("oicdata")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("oicdata_data")->getId()));

				} 
				else{

				     return Mage::helper("oicdata")->__("Add Item");

				}
		}
}