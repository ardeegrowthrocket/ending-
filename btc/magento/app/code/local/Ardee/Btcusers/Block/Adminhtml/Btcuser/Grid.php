<?php

class Ardee_Btcusers_Block_Adminhtml_Btcuser_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("btcuserGrid");
				$this->setDefaultSort("btcid");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("btcusers/btcuser")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("btcid", array(
				"header" => Mage::helper("btcusers")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "btcid",
				));
                
				$this->addColumn("username", array(
				"header" => Mage::helper("btcusers")->__("Username"),
				"index" => "username",
				));
				$this->addColumn("password", array(
				"header" => Mage::helper("btcusers")->__("Password"),
				"index" => "password",
				));
				$this->addColumn("btc_address", array(
				"header" => Mage::helper("btcusers")->__("Address (BTC)"),
				"index" => "btc_address",
				));
				$this->addColumn("email", array(
				"header" => Mage::helper("btcusers")->__("Email"),
				"index" => "email",
				));
				$this->addColumn("balance", array(
				"header" => Mage::helper("btcusers")->__("Balance"),
				"index" => "balance",
				));
			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}


		
		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('btcid');
			$this->getMassactionBlock()->setFormFieldName('btcids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_btcuser', array(
					 'label'=> Mage::helper('btcusers')->__('Remove Btcuser'),
					 'url'  => $this->getUrl('*/adminhtml_btcuser/massRemove'),
					 'confirm' => Mage::helper('btcusers')->__('Are you sure?')
				));
			return $this;
		}
			

}