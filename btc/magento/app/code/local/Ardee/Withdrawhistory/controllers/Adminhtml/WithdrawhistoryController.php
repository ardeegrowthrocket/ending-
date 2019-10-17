<?php

class Ardee_Withdrawhistory_Adminhtml_WithdrawhistoryController extends Mage_Adminhtml_Controller_Action
{
		protected function _isAllowed()
		{
		//return Mage::getSingleton('admin/session')->isAllowed('withdrawhistory/withdrawhistory');
			return true;
		}

		protected function _initAction()
		{
				$this->loadLayout()->_setActiveMenu("withdrawhistory/withdrawhistory")->_addBreadcrumb(Mage::helper("adminhtml")->__("Withdrawhistory  Manager"),Mage::helper("adminhtml")->__("Withdrawhistory Manager"));
				return $this;
		}
		public function indexAction() 
		{
			    $this->_title($this->__("Withdrawhistory"));
			    $this->_title($this->__("Manager Withdrawhistory"));

				$this->_initAction();
				$this->renderLayout();
		}
		public function editAction()
		{			    
			    $this->_title($this->__("Withdrawhistory"));
				$this->_title($this->__("Withdrawhistory"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");
				$model = Mage::getModel("withdrawhistory/withdrawhistory")->load($id);
				if ($model->getId()) {
					Mage::register("withdrawhistory_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("withdrawhistory/withdrawhistory");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Withdrawhistory Manager"), Mage::helper("adminhtml")->__("Withdrawhistory Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Withdrawhistory Description"), Mage::helper("adminhtml")->__("Withdrawhistory Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("withdrawhistory/adminhtml_withdrawhistory_edit"))->_addLeft($this->getLayout()->createBlock("withdrawhistory/adminhtml_withdrawhistory_edit_tabs"));
					$this->renderLayout();
				} 
				else {
					Mage::getSingleton("adminhtml/session")->addError(Mage::helper("withdrawhistory")->__("Item does not exist."));
					$this->_redirect("*/*/");
				}
		}

		public function newAction()
		{

		$this->_title($this->__("Withdrawhistory"));
		$this->_title($this->__("Withdrawhistory"));
		$this->_title($this->__("New Item"));

        $id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("withdrawhistory/withdrawhistory")->load($id);

		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}

		Mage::register("withdrawhistory_data", $model);

		$this->loadLayout();
		$this->_setActiveMenu("withdrawhistory/withdrawhistory");

		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Withdrawhistory Manager"), Mage::helper("adminhtml")->__("Withdrawhistory Manager"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Withdrawhistory Description"), Mage::helper("adminhtml")->__("Withdrawhistory Description"));


		$this->_addContent($this->getLayout()->createBlock("withdrawhistory/adminhtml_withdrawhistory_edit"))->_addLeft($this->getLayout()->createBlock("withdrawhistory/adminhtml_withdrawhistory_edit_tabs"));

		$this->renderLayout();

		}
		public function saveAction()
		{

			$post_data=$this->getRequest()->getPost();


				if ($post_data) {

					try {

						

						$model = Mage::getModel("withdrawhistory/withdrawhistory")
						->addData($post_data)
						->setId($this->getRequest()->getParam("id"))
						->save();

						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Withdrawhistory was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setWithdrawhistoryData(false);

						if ($this->getRequest()->getParam("back")) {
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
						$this->_redirect("*/*/");
						return;
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						Mage::getSingleton("adminhtml/session")->setWithdrawhistoryData($this->getRequest()->getPost());
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
						$model = Mage::getModel("withdrawhistory/withdrawhistory");
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
				$ids = $this->getRequest()->getPost('btchistoryids', array());
				foreach ($ids as $id) {
                      $model = Mage::getModel("withdrawhistory/withdrawhistory");
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
			$fileName   = 'withdrawhistory.csv';
			$grid       = $this->getLayout()->createBlock('withdrawhistory/adminhtml_withdrawhistory_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'withdrawhistory.xml';
			$grid       = $this->getLayout()->createBlock('withdrawhistory/adminhtml_withdrawhistory_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
}
