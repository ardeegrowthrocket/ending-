<?php

class Ardee_Withdrawhistory_Block_Adminhtml_Withdrawhistory_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("withdrawhistoryGrid");
				$this->setDefaultSort("btchistoryid");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("withdrawhistory/withdrawhistory")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("btchistoryid", array(
				"header" => Mage::helper("withdrawhistory")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "btchistoryid",
				));
                
				$this->addColumn("username", array(
				"header" => Mage::helper("withdrawhistory")->__("username"),
				"index" => "username",
				));
				$this->addColumn("amount", array(
				"header" => Mage::helper("withdrawhistory")->__("amount"),
				"index" => "amount",
				));
				$this->addColumn("btcaddress", array(
				"header" => Mage::helper("withdrawhistory")->__("btcaddress"),
				"index" => "btcaddress",
				));
						$this->addColumn('status', array(
						'header' => Mage::helper('withdrawhistory')->__('status'),
						'index' => 'status',
						'type' => 'options',
						'options'=>Ardee_Withdrawhistory_Block_Adminhtml_Withdrawhistory_Grid::getOptionArray3(),				
						));
						
				$this->addColumn("oldbalance", array(
				"header" => Mage::helper("withdrawhistory")->__("oldbalance"),
				"index" => "oldbalance",
				));
				$this->addColumn("newbalance", array(
				"header" => Mage::helper("withdrawhistory")->__("newbalance"),
				"index" => "newbalance",
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
			$this->setMassactionIdField('btchistoryid');
			$this->getMassactionBlock()->setFormFieldName('btchistoryids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_withdrawhistory', array(
					 'label'=> Mage::helper('withdrawhistory')->__('Remove Withdrawhistory'),
					 'url'  => $this->getUrl('*/adminhtml_withdrawhistory/massRemove'),
					 'confirm' => Mage::helper('withdrawhistory')->__('Are you sure?')
				));
			return $this;
		}
			
		static public function getOptionArray3()
		{
            $data_array=array(); 
			$data_array[0]='Yes';
			$data_array[1]='No';
            return($data_array);
		}
		static public function getValueArray3()
		{
            $data_array=array();
			foreach(Ardee_Withdrawhistory_Block_Adminhtml_Withdrawhistory_Grid::getOptionArray3() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		

}