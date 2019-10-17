<?php
class Ardee_Btcusers_Block_Adminhtml_Btcuser_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("btcuser_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("btcusers")->__("Item Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("btcusers")->__("Item Information"),
				"title" => Mage::helper("btcusers")->__("Item Information"),
				"content" => $this->getLayout()->createBlock("btcusers/adminhtml_btcuser_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
