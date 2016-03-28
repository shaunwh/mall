<?php

class Magentothem_Custommenu_Block_Adminhtml_Custommenu_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('custommenu_form', array('legend'=>Mage::helper('custommenu')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('custommenu')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('custommenu')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('custommenu')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('custommenu')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('custommenu')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('custommenu')->__('Content'),
          'title'     => Mage::helper('custommenu')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getCustommenuData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getCustommenuData());
          Mage::getSingleton('adminhtml/session')->setCustommenuData(null);
      } elseif ( Mage::registry('custommenu_data') ) {
          $form->setValues(Mage::registry('custommenu_data')->getData());
      }
      return parent::_prepareForm();
  }
}