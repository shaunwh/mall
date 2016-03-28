<?php

class Magentothem_Vmegamenu_Block_Adminhtml_Vmegamenu_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('vmegamenu_form', array('legend'=>Mage::helper('vmegamenu')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('vmegamenu')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('vmegamenu')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('vmegamenu')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('vmegamenu')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('vmegamenu')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('vmegamenu')->__('Content'),
          'title'     => Mage::helper('vmegamenu')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getVmegamenuData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getVmegamenuData());
          Mage::getSingleton('adminhtml/session')->setVmegamenuData(null);
      } elseif ( Mage::registry('vmegamenu_data') ) {
          $form->setValues(Mage::registry('vmegamenu_data')->getData());
      }
      return parent::_prepareForm();
  }
}