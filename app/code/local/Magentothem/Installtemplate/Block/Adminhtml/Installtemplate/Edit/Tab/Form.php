<?php

class Magentothem_Installtemplate_Block_Adminhtml_Installtemplate_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('installtemplate_form', array('legend'=>Mage::helper('installtemplate')->__('Setting')));
     
//         $fieldset->addField('store_id', 'select', array(
//            'name' => 'store',
//            'title' => Mage::helper('cms')->__('Store View'),
//            'label' => Mage::helper('cms')->__('Store View'),
//            'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
//        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_ids', 'multiselect', array(
                'name' => 'store_ids[]',
                'label' => $this->__('Store View'),
                'required' => TRUE,
                'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(FALSE, TRUE),
            ));
        }
        $fieldset->addField('action', 'select', array(
            'name' => 'action',
            'title' => Mage::helper('cms')->__('Store View'),
            'label' => Mage::helper('cms')->__('Action'),
            'values' => array(
                array('value' => 'install', 'label' => 'Install Template'),
                array('value' => 'uninstall', 'label' => 'Uninstall Template'),
            ),
        ));

        if ( Mage::getSingleton('adminhtml/session')->getInstalltemplateData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getInstalltemplateData());
          Mage::getSingleton('adminhtml/session')->setInstalltemplateData(null);
      } elseif ( Mage::registry('installtemplate_data') ) {
          $form->setValues(Mage::registry('installtemplate_data')->getData());
      }
      return parent::_prepareForm();
  }
}