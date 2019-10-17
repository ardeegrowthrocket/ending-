<?php
class Ardee_Btcusers_Block_Adminhtml_Btcuser_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("btcusers_form", array("legend"=>Mage::helper("btcusers")->__("Item information")));

				
						$fieldset->addField("username", "text", array(
						"label" => Mage::helper("btcusers")->__("Username"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "username",
						));
					
						$fieldset->addField("password", "text", array(
						"label" => Mage::helper("btcusers")->__("Password"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "password",
						));
					
						$fieldset->addField("btc_address", "text", array(
						"label" => Mage::helper("btcusers")->__("Address (BTC)"),
						"name" => "btc_address",
						));
					
						$fieldset->addField("email", "text", array(
						"label" => Mage::helper("btcusers")->__("Email"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "email",
						));
					
						$fieldset->addField("balance", "text", array(
						"label" => Mage::helper("btcusers")->__("Balance"),
						"name" => "balance",
						));
					

				if (Mage::getSingleton("adminhtml/session")->getBtcuserData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getBtcuserData());
					Mage::getSingleton("adminhtml/session")->setBtcuserData(null);
				} 
				elseif(Mage::registry("btcuser_data")) {
				    $form->setValues(Mage::registry("btcuser_data")->getData());
				}
				return parent::_prepareForm();
		}
}
