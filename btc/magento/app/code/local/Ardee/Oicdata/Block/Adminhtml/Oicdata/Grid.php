<?php

class Ardee_Oicdata_Block_Adminhtml_Oicdata_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("oicdataGrid");
				$this->setDefaultSort("oic_id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("oicdata/oicdata")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("oic_id", array(
				"header" => Mage::helper("oicdata")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "oic_id",
				));
                
					$this->addColumn('ico_start', array(
						'header'    => Mage::helper('oicdata')->__('ICO Start'),
						'index'     => 'ico_start',
						'type'      => 'datetime',
					));
					$this->addColumn('ico_end', array(
						'header'    => Mage::helper('oicdata')->__('ICO End'),
						'index'     => 'ico_end',
						'type'      => 'datetime',
					));
				$this->addColumn("ico_qty", array(
				"header" => Mage::helper("oicdata")->__("Coins to sell"),
				"index" => "ico_qty",
				));
				$this->addColumn("ico_price", array(
				"header" => Mage::helper("oicdata")->__("Price"),
				"index" => "ico_price",
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
			$this->setMassactionIdField('oic_id');
			$this->getMassactionBlock()->setFormFieldName('oic_ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_oicdata', array(
					 'label'=> Mage::helper('oicdata')->__('Remove Oicdata'),
					 'url'  => $this->getUrl('*/adminhtml_oicdata/massRemove'),
					 'confirm' => Mage::helper('oicdata')->__('Are you sure?')
				));
			return $this;
		}
			

}