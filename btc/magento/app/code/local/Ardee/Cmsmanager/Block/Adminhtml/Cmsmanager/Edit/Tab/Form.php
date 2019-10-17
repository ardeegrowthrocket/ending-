<?php
class Ardee_Cmsmanager_Block_Adminhtml_Cmsmanager_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("cmsmanager_form", array("legend"=>Mage::helper("cmsmanager")->__("Item information")));

				
						$fieldset->addField("title", "text", array(
						"label" => Mage::helper("cmsmanager")->__("Title"),
						"name" => "title",
						));
					
						$fieldset->addField("content", "textarea", array(
						"label" => Mage::helper("cmsmanager")->__("Content"),
						"name" => "content",
						));
					

				if (Mage::getSingleton("adminhtml/session")->getCmsmanagerData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getCmsmanagerData());
					Mage::getSingleton("adminhtml/session")->setCmsmanagerData(null);
				} 
				elseif(Mage::registry("cmsmanager_data")) {
				    $form->setValues(Mage::registry("cmsmanager_data")->getData());
				}
				return parent::_prepareForm();
		}
}
