<?php

class Mind_Rate_Block_Adminhtml_Rate_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('rate_form', array('legend'=>Mage::helper('rate')->__('Item information')));
     
      $fieldset->addField('rate_name', 'text', array(
          'label'     => Mage::helper('rate')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'rate_name',
      ));
      $fieldset->addField('rate_start', 'text', array(
          'label'     => Mage::helper('rate')->__('Payin'),
          'class'     => 'required-entry validate-number',
          'required'  => true,
          'name'      => 'rate_start',
      ));
      $fieldset->addField('activated', 'select', array(
          'label'     => Mage::helper('rate')->__('Status'),
          'name'      => 'activated',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('rate')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('rate')->__('Disabled'),
              ),
          ),
      ));
      $fieldset->addField('rate_bet', 'select', array(
          'label'     => Mage::helper('rate')->__('Betting is on Going?(If this set to No. no one can set bet on your event)'),
          'name'      => 'activated',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('rate')->__('Yes'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('rate')->__('No'),
              ),
          ),
      ));
          
     
      if ( Mage::getSingleton('adminhtml/session')->getRateData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getRateData());
          Mage::getSingleton('adminhtml/session')->setRateData(null);
      } elseif ( Mage::registry('rate_data') ) {
          $form->setValues(Mage::registry('rate_data')->getData());
      }
      return parent::_prepareForm();
  }
}