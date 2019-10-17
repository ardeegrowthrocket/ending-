<?php
class Ardee_Withdrawhistory_Block_Adminhtml_Withdrawhistory_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("withdrawhistory_form", array("legend"=>Mage::helper("withdrawhistory")->__("Item information")));

				
						$fieldset->addField("username", "text", array(
						"label" => Mage::helper("withdrawhistory")->__("username"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "username",
						));
					
						$fieldset->addField("amount", "text", array(
						"label" => Mage::helper("withdrawhistory")->__("amount"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "amount",
						));
					
						$fieldset->addField("btcaddress", "text", array(
						"label" => Mage::helper("withdrawhistory")->__("btcaddress"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "btcaddress",
						));
									
						 $fieldset->addField('status', 'select', array(
						'label'     => Mage::helper('withdrawhistory')->__('status'),
						'values'   => Ardee_Withdrawhistory_Block_Adminhtml_Withdrawhistory_Grid::getValueArray3(),
						'name' => 'status',					
						"class" => "required-entry",
						"required" => true,
						));
						$fieldset->addField("oldbalance", "text", array(
						"label" => Mage::helper("withdrawhistory")->__("oldbalance"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "oldbalance",
						));
					
						$fieldset->addField("newbalance", "text", array(
						"label" => Mage::helper("withdrawhistory")->__("newbalance"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "newbalance",
						));
					

				if (Mage::getSingleton("adminhtml/session")->getWithdrawhistoryData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getWithdrawhistoryData());
					Mage::getSingleton("adminhtml/session")->setWithdrawhistoryData(null);
				} 
				elseif(Mage::registry("withdrawhistory_data")) {
				    $form->setValues(Mage::registry("withdrawhistory_data")->getData());
				}
				return parent::_prepareForm();
		}
}
