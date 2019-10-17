<?php

class Ardee_Cmsmanager_Block_Adminhtml_Cmsmanager_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("cmsmanagerGrid");
				$this->setDefaultSort("cms_id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("cmsmanager/cmsmanager")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("cms_id", array(
				"header" => Mage::helper("cmsmanager")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "cms_id",
				));
                
				$this->addColumn("title", array(
				"header" => Mage::helper("cmsmanager")->__("Title"),
				"index" => "title",
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
			$this->setMassactionIdField('cms_id');
			$this->getMassactionBlock()->setFormFieldName('cms_ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_cmsmanager', array(
					 'label'=> Mage::helper('cmsmanager')->__('Remove Cmsmanager'),
					 'url'  => $this->getUrl('*/adminhtml_cmsmanager/massRemove'),
					 'confirm' => Mage::helper('cmsmanager')->__('Are you sure?')
				));
			return $this;
		}
			

}