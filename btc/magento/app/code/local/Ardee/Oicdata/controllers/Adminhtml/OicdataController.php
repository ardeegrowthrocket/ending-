<?php

class Ardee_Oicdata_Adminhtml_OicdataController extends Mage_Adminhtml_Controller_Action
{
		protected function _isAllowed()
		{
		//return Mage::getSingleton('admin/session')->isAllowed('oicdata/oicdata');
			return true;
		}

		protected function _initAction()
		{
				$this->loadLayout()->_setActiveMenu("oicdata/oicdata")->_addBreadcrumb(Mage::helper("adminhtml")->__("Oicdata  Manager"),Mage::helper("adminhtml")->__("Oicdata Manager"));
				return $this;
		}
		public function indexAction() 
		{
			    $this->_title($this->__("Oicdata"));
			    $this->_title($this->__("Manager Oicdata"));

				$this->_initAction();
				$this->renderLayout();
		}
		public function editAction()
		{			    
			    $this->_title($this->__("Oicdata"));
				$this->_title($this->__("Oicdata"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");
				$model = Mage::getModel("oicdata/oicdata")->load($id);
				if ($model->getId()) {
					Mage::register("oicdata_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("oicdata/oicdata");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Oicdata Manager"), Mage::helper("adminhtml")->__("Oicdata Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Oicdata Description"), Mage::helper("adminhtml")->__("Oicdata Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("oicdata/adminhtml_oicdata_edit"))->_addLeft($this->getLayout()->createBlock("oicdata/adminhtml_oicdata_edit_tabs"));
					$this->renderLayout();
				} 
				else {
					Mage::getSingleton("adminhtml/session")->addError(Mage::helper("oicdata")->__("Item does not exist."));
					$this->_redirect("*/*/");
				}
		}

		public function newAction()
		{

		$this->_title($this->__("Oicdata"));
		$this->_title($this->__("Oicdata"));
		$this->_title($this->__("New Item"));

        $id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("oicdata/oicdata")->load($id);

		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}

		Mage::register("oicdata_data", $model);

		$this->loadLayout();
		$this->_setActiveMenu("oicdata/oicdata");

		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Oicdata Manager"), Mage::helper("adminhtml")->__("Oicdata Manager"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Oicdata Description"), Mage::helper("adminhtml")->__("Oicdata Description"));


		$this->_addContent($this->getLayout()->createBlock("oicdata/adminhtml_oicdata_edit"))->_addLeft($this->getLayout()->createBlock("oicdata/adminhtml_oicdata_edit_tabs"));

		$this->renderLayout();

		}
		public function saveAction()
		{

			$post_data=$this->getRequest()->getPost();


				if ($post_data) {

					try {

						

						$model = Mage::getModel("oicdata/oicdata")
						->addData($post_data)
						->setId($this->getRequest()->getParam("id"))
						->save();

						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Oicdata was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setOicdataData(false);

						if ($this->getRequest()->getParam("back")) {
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
						$this->_redirect("*/*/");
						return;
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						Mage::getSingleton("adminhtml/session")->setOicdataData($this->getRequest()->getPost());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					return;
					}

				}
				$this->_redirect("*/*/");
		}



		public function deleteAction()
		{
				if( $this->getRequest()->getParam("id") > 0 ) {
					try {
						$model = Mage::getModel("oicdata/oicdata");
						$model->setId($this->getRequest()->getParam("id"))->delete();
						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
						$this->_redirect("*/*/");
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					}
				}
				$this->_redirect("*/*/");
		}

		
		public function massRemoveAction()
		{
			try {
				$ids = $this->getRequest()->getPost('oic_ids', array());
				foreach ($ids as $id) {
                      $model = Mage::getModel("oicdata/oicdata");
					  $model->setId($id)->delete();
				}
				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully removed"));
			}
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
			}
			$this->_redirect('*/*/');
		}
			
		/**
		 * Export order grid to CSV format
		 */
		public function exportCsvAction()
		{
			$fileName   = 'oicdata.csv';
			$grid       = $this->getLayout()->createBlock('oicdata/adminhtml_oicdata_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'oicdata.xml';
			$grid       = $this->getLayout()->createBlock('oicdata/adminhtml_oicdata_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
}
