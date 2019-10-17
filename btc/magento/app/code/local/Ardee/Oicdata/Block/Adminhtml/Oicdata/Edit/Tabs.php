<?php
class Ardee_Oicdata_Block_Adminhtml_Oicdata_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("oicdata_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("oicdata")->__("Item Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("oicdata")->__("Item Information"),
				"title" => Mage::helper("oicdata")->__("Item Information"),
				"content" => $this->getLayout()->createBlock("oicdata/adminhtml_oicdata_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
