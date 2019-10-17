<?php
class Ardee_Oicdata_Block_Adminhtml_Oicdata_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("oicdata_form", array("legend"=>Mage::helper("oicdata")->__("Item information")));

				
						$dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(
							Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
						);

						$fieldset->addField('ico_start', 'date', array(
						'label'        => Mage::helper('oicdata')->__('ICO Start'),
						'name'         => 'ico_start',					
						"class" => "required-entry",
						"required" => true,
						'time' => true,
						'image'        => $this->getSkinUrl('images/grid-cal.gif'),
						'format'       => $dateFormatIso
						));
						$dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(
							Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
						);

						$fieldset->addField('ico_end', 'date', array(
						'label'        => Mage::helper('oicdata')->__('ICO End'),
						'name'         => 'ico_end',					
						"class" => "required-entry",
						"required" => true,
						'time' => true,
						'image'        => $this->getSkinUrl('images/grid-cal.gif'),
						'format'       => $dateFormatIso
						));
						$fieldset->addField("ico_qty", "text", array(
						"label" => Mage::helper("oicdata")->__("Coins to sell"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "ico_qty",
						));
						$fieldset->addField("ico_limit", "text", array(
						"label" => Mage::helper("oicdata")->__("Limit of coins to buy per person a day."),					
						"class" => "required-entry",
						"required" => true,
						"name" => "ico_limit",
						));					
						$fieldset->addField("ico_price", "text", array(
						"label" => Mage::helper("oicdata")->__("Price"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "ico_price",
						));
					

				if (Mage::getSingleton("adminhtml/session")->getOicdataData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getOicdataData());
					Mage::getSingleton("adminhtml/session")->setOicdataData(null);
				} 
				elseif(Mage::registry("oicdata_data")) {
				    $form->setValues(Mage::registry("oicdata_data")->getData());
				}
				return parent::_prepareForm();
		}
}
